<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use App\Models\FinanceSetting;
use App\Models\GrubkasActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $setting = FinanceSetting::singleton();

        $members = Datasikadmodel::with('latestIuran')
            ->orderBy('nama')
            ->get();

        $totalKas = (int) GrubkasActivityLog::query()
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'out' THEN -amount ELSE amount END), 0) as total")
            ->value('total');

        $totalPengeluaran = (int) GrubkasActivityLog::query()
            ->where('direction', 'out')
            ->sum('amount');

        $jumlahTransaksi = (int) GrubkasActivityLog::query()->count();

        $pembayaranBerhasil = (int) GrubkasActivityLog::query()
            ->where('direction', 'in')
            ->whereIn('transaction_status', ['capture', 'settlement'])
            ->count();

        // Get activity logs with pagination (6 per page)
        $allActivityLogs = GrubkasActivityLog::latest('occurred_at')
            ->latest('id')
            ->paginate(6);

        // Get all logs for summary stats (not paginated)
        $allLogsForStats = GrubkasActivityLog::latest('occurred_at')
            ->latest('id')
            ->get();

        return view('admin.finance.index', compact(
            'setting',
            'members',
            'allActivityLogs',
            'allLogsForStats',
            'totalKas',
            'totalPengeluaran',
            'jumlahTransaksi',
            'pembayaranBerhasil'
        ));
    }

    public function updateWeeklyFee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weekly_fee' => ['required', 'integer', 'min:0', 'max:100000000'],
        ]);

        $setting = FinanceSetting::singleton();
        $oldFee = $setting->weekly_fee;
        $newFee = (int) $validated['weekly_fee'];
        $setting->update([
            'weekly_fee' => $newFee,
        ]);

        // Buat log perubahan iuran mingguan
        $user = Auth::user();
        $userName = $user?->name ?? 'Unknown User';

        GrubkasActivityLog::create([
            'user_name' => $userName,
            'activity_type' => 'weekly_fee_setting',
            'direction' => 'neutral',
            'amount' => 0,
            'title' => 'Pengaturan Iuran Mingguan',
            'description' => 'Iuran mingguan diubah dari Rp ' . number_format($oldFee, 0, ',', '.') . ' menjadi Rp ' . number_format($newFee, 0, ',', '.'),
            'order_id' => 'SETTING-' . now()->format('YmdHis') . '-' . random_int(1000, 9999),
            'transaction_status' => 'manual',
            'occurred_at' => now(),
        ]);

        return redirect()->route('admin.finance.index')->with('success', 'Iuran mingguan berhasil diperbarui.');
    }

    public function updateMemberBalance(Request $request, string $nim): RedirectResponse
    {
        $validated = $request->validate([
            'nominal' => ['required', 'integer', 'min:0', 'max:100000000'],
            'saldo_lebih' => ['required', 'integer', 'min:0', 'max:100000000'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $member = Datasikadmodel::where('Nim', $nim)->firstOrFail();
        $latestIuran = $member->iuran()->latest('id')->first();

        // Simpan nilai lama untuk tracking perubahan
        $oldUtang = (int) optional($latestIuran)->Nominal;
        $oldSaldoPositif = (int) optional($latestIuran)->Saldo_Lebih;

        // Ambil nilai input
        $inputUtang = (int) $validated['nominal'];
        $inputSaldoPositif = (int) $validated['saldo_lebih'];

        // Logika pemotongan: jika ada saldo positif dan utang, potong utang dengan saldo positif
        $finalUtang = $inputUtang;
        $finalSaldoPositif = $inputSaldoPositif;

        if ($finalSaldoPositif > 0 && $finalUtang > 0) {
            // Saldo positif potong utang
            $finalUtang = $finalUtang - $finalSaldoPositif;

            if ($finalUtang < 0) {
                // Jika utang kurang dari 0, sisanya jadi saldo positif
                $finalSaldoPositif = abs($finalUtang);
                $finalUtang = 0;
            } else {
                // Jika utang masih ada, saldo positif jadi 0 (sudah habis potong utang)
                $finalSaldoPositif = 0;
            }
        }

        $payload = [
            'Nominal' => $finalUtang,
            'Saldo_Lebih' => $finalSaldoPositif,
            'Status_Bayar' => $finalUtang === 0 ? 1 : 0,
            'Keterangan' => $validated['keterangan'] ?? 'Penyesuaian manual oleh admin',
        ];

        if ($latestIuran) {
            $latestIuran->update($payload);
        } else {
            $member->iuran()->create(array_merge($payload, [
                'user_nim' => $member->Nim,
            ]));
        }

        // Buat log perubahan saldo
        $changeDetails = [];
        if ($oldUtang !== $finalUtang) {
            $changeDetails[] = "Utang: Rp " . number_format($oldUtang, 0, ',', '.') . " → Rp " . number_format($finalUtang, 0, ',', '.');
        }
        if ($oldSaldoPositif !== $finalSaldoPositif) {
            $changeDetails[] = "Saldo Positif: Rp " . number_format($oldSaldoPositif, 0, ',', '.') . " → Rp " . number_format($finalSaldoPositif, 0, ',', '.');
        }

        if (!empty($changeDetails)) {
            $user = Auth::user();
            $roleLabel = $user?->role === 'akuntan' ? 'Akuntan' : 'Admin';
            $userDisplayName = ($user?->name ?? 'Unknown') . ' (' . $roleLabel . ')';

            $description = implode(' | ', $changeDetails);
            if ($inputSaldoPositif > 0 && $inputUtang > 0 && $finalSaldoPositif !== $inputSaldoPositif) {
                $description .= ' | Saldo positif dipotong untuk membayar utang';
            }
            $description .= ' | Anggota: ' . $member->nama . ' (' . $member->Nim . ')';

            GrubkasActivityLog::create([
                'user_nim' => null,
                'user_name' => $userDisplayName,
                'activity_type' => 'balance_adjustment',
                'direction' => 'neutral',
                'amount' => 0,
                'title' => 'Penyesuaian Saldo Manual',
                'description' => $description,
                'order_id' => 'ADJ-' . now()->format('YmdHis') . '-' . random_int(1000, 9999),
                'transaction_status' => 'manual',
                'occurred_at' => now(),
            ]);
        }

        return redirect()->route('admin.finance.index')->with('success', 'Utang/saldo anggota berhasil diperbarui dengan penyesuaian otomatis.');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:100000000'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        GrubkasActivityLog::create([
            'activity_type' => 'expense',
            'direction' => 'out',
            'amount' => (int) $validated['amount'],
            'title' => 'Pengeluaran Kas',
            'description' => $validated['description'],
            'order_id' => 'EXP-' . now()->format('YmdHis') . '-' . random_int(1000, 9999),
            'transaction_status' => 'manual',
            'occurred_at' => now(),
        ]);

        return redirect()->route('admin.finance.index')->with('success', 'Pengeluaran manual berhasil dicatat.');
    }

    public function storeCashAdjustment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:100000000'],
            'adjustment_type' => ['required', 'in:add,subtract'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $amount = (int) $validated['amount'];
        $direction = $validated['adjustment_type'] === 'subtract' ? 'out' : 'in';
        $title = $direction === 'out' ? 'Penyesuaian Kas Manual (Kurang)' : 'Penyesuaian Kas Manual (Tambah)';
        $description = trim((string) ($validated['description'] ?? ''));

        if ($description === '') {
            $description = $direction === 'out'
                ? 'Sinkronisasi total kas oleh admin: pengurangan manual'
                : 'Sinkronisasi total kas oleh admin: penambahan manual';
        }

        GrubkasActivityLog::create([
            'activity_type' => 'manual_adjustment',
            'direction' => $direction,
            'amount' => $amount,
            'title' => $title,
            'description' => $description,
            'order_id' => 'ADJ-' . now()->format('YmdHis') . '-' . random_int(1000, 9999),
            'transaction_status' => 'manual',
            'occurred_at' => now(),
        ]);

        return redirect()->route('admin.finance.index')->with('success', 'Penyesuaian total kas berhasil disinkronkan.');
    }

    public function showDataCalibrationForm()
    {
        // Hanya Admin yang bisa akses
        if (Auth::user()?->role !== 'admin') {
            return redirect()->route('admin.finance.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengakses fitur ini.');
        }

        $totalActivityLogs = GrubkasActivityLog::count();
        $totalMembers = Datasikadmodel::count();
        $totalIuranRecords = Datasikadmodel::query()
            ->whereHas('iuran')
            ->count();

        return view('admin.finance.calibration', compact(
            'totalActivityLogs',
            'totalMembers',
            'totalIuranRecords'
        ));
    }

    public function executeDataCalibration(Request $request): RedirectResponse
    {
        // Hanya Admin yang bisa akses
        if (Auth::user()?->role !== 'admin') {
            return redirect()->route('admin.finance.index')->with('error', 'Akses ditolak. Hanya Admin yang dapat melakukan operasi ini.');
        }

        // Validasi confirmation code
        $validated = $request->validate([
            'confirmation_code' => ['required', 'string'],
            'confirmation_checkbox' => ['required', 'accepted'],
        ]);

        $adminName = Auth::user()?->name ?? 'Unknown';
        $expectedCode = 'RESET-' . now()->format('Ymd');

        if ($validated['confirmation_code'] !== $expectedCode) {
            return redirect()->route('admin.finance.calibration')->with('error', 'Kode konfirmasi salah. Mohon coba lagi.');
        }

        try {
            // Hapus semua activity logs (termasuk log kalibrasi sebelumnya)
            $deletedLogs = GrubkasActivityLog::count();
            GrubkasActivityLog::truncate();

            // Hapus/reset semua iuran data dari members
            $datasikadWithIuran = Datasikadmodel::query()
                ->whereHas('iuran')
                ->get();
            
            $deletedIuran = 0;
            foreach ($datasikadWithIuran as $member) {
                $deletedIuran += $member->iuran()->count();
                $member->iuran()->delete();
            }

            return redirect()->route('admin.finance.index')->with('success', "✅ Kalibrasi berhasil! Dihapus: $deletedLogs log transaksi dan $deletedIuran data iuran. Sistem sudah benar-benar bersih.");
        } catch (\Exception $e) {
            return redirect()->route('admin.finance.calibration')->with('error', 'Terjadi error saat kalibrasi: ' . $e->getMessage());
        }
    }
}
