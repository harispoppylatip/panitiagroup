<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use App\Models\FinanceSetting;
use App\Models\GrubkasActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        $recentExpenses = GrubkasActivityLog::where('direction', 'out')
            ->latest('occurred_at')
            ->latest('id')
            ->limit(10)
            ->get();

        return view('admin.finance.index', compact(
            'setting',
            'members',
            'recentExpenses',
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
        $setting->update([
            'weekly_fee' => (int) $validated['weekly_fee'],
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

        $payload = [
            'Nominal' => (int) $validated['nominal'],
            'Saldo_Lebih' => (int) $validated['saldo_lebih'],
            'Status_Bayar' => (int) $validated['nominal'] === 0 ? 1 : 0,
            'Keterangan' => $validated['keterangan'] ?? 'Penyesuaian manual oleh admin',
        ];

        if ($latestIuran) {
            $latestIuran->update($payload);
        } else {
            $member->iuran()->create(array_merge($payload, [
                'user_nim' => $member->Nim,
            ]));
        }

        return redirect()->route('admin.finance.index')->with('success', 'Utang/saldo anggota berhasil diperbarui.');
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
}
