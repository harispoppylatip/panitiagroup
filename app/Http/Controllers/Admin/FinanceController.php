<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Datasikadmodel;
use App\Models\grubkas;
use App\Models\payment\GrubkasDashboard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $payments = grubkas::with(['datasikad', 'Status'])
            ->orderByDesc('updated_at')
            ->get();

        $settings = $this->ambilSettingFinance();
        $weeklyFee = (int) ($settings['weekly_fee'] ?? 10000);

        $pendingPayments = $payments
            ->filter(fn ($payment) => (int) $payment->Status_Pembayaran === 2)
            ->map(function ($payment) {
                $statusLabel = $payment->Status?->Status ?? match ((int) $payment->Status_Pembayaran) {
                    1 => 'Belum Bayar',
                    2 => 'Menunggu Konfirmasi',
                    3 => 'Sudah Bayar',
                    4 => 'Ditolak',
                    default => 'Status Tidak Diketahui',
                };
                $name = $payment->datasikad?->nama ?? 'Tanpa Nama';
                $amount = (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih));

                return [
                    'name' => $name,
                    'initial' => strtoupper(substr($name ?: $payment->Nim_key, 0, 2)),
                    'nim' => $payment->Nim_key,
                    'week' => $statusLabel,
                    'time' => Carbon::parse($payment->updated_at ?? $payment->created_at)->format('d M Y H:i'),
                    'amount' => '+Rp ' . number_format($amount, 0, ',', '.'),
                    'file' => $payment->Bukti_Pembayaran ? basename($payment->Bukti_Pembayaran) : 'Belum ada bukti',
                    'proof_url' => $payment->Bukti_Pembayaran ? asset('storage/' . $payment->Bukti_Pembayaran) : null,
                    'proof_name' => $payment->Bukti_Pembayaran ? basename($payment->Bukti_Pembayaran) : null,
                    'status_label' => $statusLabel,
                    'note' => $payment->Keterangan ?: 'Data diambil dari grubkas_info',
                ];
            })
            ->values();

        $historyPayments = $payments
            ->filter(fn ($payment) => (int) $payment->Status_Pembayaran === 3)
            ->map(function ($payment) {
                $name = $payment->datasikad?->nama ?? 'Tanpa Nama';
                $amount = (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih));

                return [
                    'name' => $name,
                    'initial' => strtoupper(substr($name ?: $payment->Nim_key, 0, 2)),
                    'nim' => $payment->Nim_key,
                    'week' => $payment->Status?->Status ?? 'Sudah Bayar',
                    'time' => Carbon::parse($payment->updated_at ?? $payment->created_at)->format('d M Y H:i'),
                    'amount' => '+Rp ' . number_format($amount, 0, ',', '.'),
                    'proof_url' => $payment->Bukti_Pembayaran ? asset('storage/' . $payment->Bukti_Pembayaran) : null,
                    'proof_name' => $payment->Bukti_Pembayaran ? basename($payment->Bukti_Pembayaran) : null,
                ];
            })
            ->values();

        $activityLogs = collect();

        if (Schema::hasTable('grubkas_activity_logs')) {
            $activityLogs = DB::table('grubkas_activity_logs')
                ->orderByDesc(DB::raw('COALESCE(occurred_at, created_at)'))
                ->get()
                ->map(function ($log) {
                    $amount = (int) $log->amount;
                    $type = match ($log->direction) {
                        'in' => 'in',
                        'out' => 'out',
                        default => 'set',
                    };

                    if (!in_array($type, ['in', 'out'], true)) {
                        return null;
                    }

                    return [
                        'type' => $type,
                        'title' => $log->title,
                        'detail' => trim(($log->user_nim ? 'NIM ' . $log->user_nim . ' · ' : '') . ($log->description ?? '')),
                        'amount' => $type === 'in'
                            ? '+Rp ' . number_format($amount, 0, ',', '.')
                            : '-Rp ' . number_format($amount, 0, ',', '.'),
                        'time' => Carbon::parse($log->occurred_at ?? $log->created_at)->format('d M Y H:i'),
                    ];
                })
                ->filter()
                ->values();
        }

        $totalPemasukan = $payments
            ->filter(fn ($payment) => (int) $payment->Status_Pembayaran === 3)
            ->sum(fn ($payment) => (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih)));

        $totalPengeluaran = $activityLogs->filter(fn ($log) => $log['type'] === 'out')->sum(fn ($log) => (int) preg_replace('/[^0-9]/', '', $log['amount']));

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Data anggota dari datasikad (sumber utama)
        $memberChoices = Datasikadmodel::orderBy('nama')->get(['id', 'nama', 'Nim']);

        $memberBalances = $payments
            ->filter(fn ($payment) => in_array((int) $payment->Status_Pembayaran, [1, 3]))
            ->map(function ($payment) {
                $name = $payment->datasikad?->nama ?? 'Tanpa Nama';
                return [
                    'name' => $name,
                    'nim' => $payment->Nim_key,
                    'utang' => (int) ($payment->Utang_Anggota ?: 0),
                    'saldo_lebih' => (int) ($payment->Saldo_Lebih ?: 0),
                ];
            })
            ->filter(fn ($m) => $m['utang'] > 0 || $m['saldo_lebih'] > 0)
            ->values();

        $totalKas = $saldoAkhir;
        $pendingCount = $pendingPayments->count();
        $historyCount = $historyPayments->count();

        $stats = [
            [
                'label' => 'Total kas',
                'value' => 'Rp ' . number_format($totalKas, 0, ',', '.'),
                'meta' => 'Diperbarui dari database',
                'icon' => 'bi-wallet2',
                'tone' => 'primary',
            ],
            [
                'label' => 'Sudah bayar',
                'value' => $historyCount . ' anggota',
                'meta' => 'Berstatus lunas',
                'icon' => 'bi-people',
                'tone' => 'success',
            ],
            [
                'label' => 'Menunggu konfirmasi',
                'value' => $pendingCount,
                'meta' => 'Perlu dicek',
                'icon' => 'bi-bell',
                'tone' => 'warning',
            ],
        ];

        return view('admin.finance.keuangan', compact(
            'payments',
            'pendingPayments',
            'historyPayments',
            'activityLogs',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'settings',
            'weeklyFee',
            'memberChoices',
            'memberBalances',
            'totalKas',
            'pendingCount',
            'historyCount',
            'stats'
        ));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'weekly_fee' => 'required|integer|min:0',
        ]);

        $settings = $this->ambilSettingFinance();
        $settings['weekly_fee'] = $validated['weekly_fee'];
        GrubkasDashboard::updateOrCreate(
            ['key' => 'finance_settings'],
            ['value' => json_encode($settings)]
        );

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function storeManualCash(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|exists:datasikad,Nim',
            'amount' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $payment = grubkas::firstOrNew(['Nim_key' => $validated['nim']]);
        $payment->Nominal_Bayar = $validated['amount'];
        $payment->Status_Pembayaran = 3;
        $payment->Keterangan = $validated['description'] ?? 'Pembayaran manual oleh admin';
        $payment->save();

        $this->simpanLogAktivitas([
            'title' => 'Pembayaran manual',
            'description' => $validated['description'] ?? 'Pembayaran tunai',
            'amount' => $validated['amount'],
            'direction' => 'in',
            'user_nim' => $validated['nim'],
        ]);

        return back()->with('success', 'Pembayaran tunai berhasil dicatat.');
    }

    public function storeManualDebt(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|exists:datasikad,Nim',
            'amount' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $payment = grubkas::firstOrNew(['Nim_key' => $validated['nim']]);
        $payment->Utang_Anggota = $validated['amount'];
        $payment->Status_Pembayaran = 1;
        $payment->Keterangan = $validated['description'] ?? 'Utang dicatat oleh admin';
        $payment->save();

        return back()->with('success', 'Utang berhasil dicatat.');
    }

    public function approvePayment($nim)
    {
        $payment = grubkas::where('Nim_key', $nim)->firstOrFail();
        $payment->Status_Pembayaran = 3;
        $payment->save();

        $this->simpanLogAktivitas([
            'title' => 'Pembayaran dikonfirmasi',
            'description' => 'Pembayaran dari NIM ' . $nim . ' telah dikonfirmasi',
            'amount' => (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih)),
            'direction' => 'in',
            'user_nim' => $nim,
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function rejectPayment(Request $request, $nim)
    {
        $validated = $request->validate([
            'alasan' => 'nullable|string',
        ]);

        $payment = grubkas::where('Nim_key', $nim)->firstOrFail();
        $payment->Status_Pembayaran = 4;
        $payment->Keterangan = $validated['alasan'] ?? 'Ditolak oleh admin';
        $payment->save();

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function resetAll(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah.');
        }

        grubkas::query()->update([
            'Status_Pembayaran' => 1,
            'Nominal_Bayar' => null,
            'Bukti_Pembayaran' => null,
            'Keterangan' => null,
        ]);

        Storage::disk('public')->deleteDirectory('bukti_pembayaran');
        Storage::disk('public')->makeDirectory('bukti_pembayaran');

        return back()->with('success', 'Semua data pembayaran berhasil direset.');
    }

    private function ambilSettingFinance(): array
    {
        $setting = GrubkasDashboard::where('key', 'finance_settings')->first();

        if ($setting && $setting->value) {
            $decoded = json_decode($setting->value, true);

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return ['weekly_fee' => 10000];
    }

    private function simpanLogAktivitas(array $data): void
    {
        if (!Schema::hasTable('grubkas_activity_logs')) {
            return;
        }

        DB::table('grubkas_activity_logs')->insert([
            'title' => $data['title'] ?? 'Aktivitas',
            'description' => $data['description'] ?? '',
            'amount' => $data['amount'] ?? 0,
            'direction' => $data['direction'] ?? 'in',
            'user_nim' => $data['user_nim'] ?? null,
            'occurred_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
