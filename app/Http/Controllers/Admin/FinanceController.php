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
use Illuminate\Support\Facades\Http;
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

        // Kas disimpan sebagai key-value 'kas_total' di grubkas_dashboard,
        // independen dari status pembayaran anggota (status bisa di-reset
        // saat utang ditambahkan, kas tetap aman).
        $kas = GrubkasDashboard::totalKas();
        $totalPemasukan = $kas['in'];
        $totalPengeluaran = $kas['out'];
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Data anggota dari datasikad (sumber utama)
        $memberChoices = Datasikadmodel::orderBy('nama')->get(['id', 'nama', 'Nim']);

        $memberBalances = $payments
            ->filter(fn ($payment) => in_array((int) $payment->Status_Pembayaran, [1, 3]))
            ->map(function ($payment) {
                $name = $payment->datasikad?->nama ?? 'Tanpa Nama';
                $utang = (int) ($payment->Utang_Anggota ?: 0);
                $saldo = (int) ($payment->Saldo_Lebih ?: 0);
                return [
                    'name' => $name,
                    'nim' => $payment->Nim_key,
                    'utang' => $utang,
                    'saldo_lebih' => $saldo,
                    'sisa_utang' => max(0, $utang - $saldo),
                ];
            })
            ->filter(fn ($m) => $m['utang'] > 0 || $m['saldo_lebih'] > 0)
            ->values();

        // Daftar lengkap utang + saldo lebih untuk menu "Saldo & Utang"
        $memberSaldo = $payments
            ->map(function ($payment) {
                $utang = (int) ($payment->Utang_Anggota ?: 0);
                $saldo = (int) ($payment->Saldo_Lebih ?: 0);
                return [
                    'name' => $payment->datasikad?->nama ?? 'Tanpa Nama',
                    'nim' => $payment->Nim_key,
                    'utang' => $utang,
                    'saldo_lebih' => $saldo,
                    'sisa_utang' => max(0, $utang - $saldo),
                    'status_label' => $payment->Status?->Status ?? 'Belum Bayar',
                ];
            })
            ->filter(fn ($m) => $m['utang'] > 0 || $m['saldo_lebih'] > 0)
            ->sortByDesc('sisa_utang')
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
            'memberSaldo',
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
        GrubkasDashboard::simpanSettingFinance($settings);

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
        // Kelebihan pembayaran otomatis jadi saldo lebih anggota
        $this->alokasikanPembayaran($payment, (int) $validated['amount']);
        $payment->Keterangan = $validated['description'] ?? 'Pembayaran manual oleh admin';
        $payment->save();

        GrubkasDashboard::tambahKas('in', (int) $validated['amount']);

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
        // Tambahkan ke utang yang sudah ada, bukan menimpa
        $payment->Utang_Anggota = (int) ($payment->Utang_Anggota ?? 0) + (int) $validated['amount'];
        // Saldo lebih otomatis dipakai untuk menutup utang (baru maupun lama)
        $this->terapkanSaldoLebih($payment);
        // Kalau masih ada sisa utang berarti belum bayar, reset status ke Belum Bayar.
        // Kalau saldo lebih melunasi seluruh utang, status tetap (tidak direset).
        // Kas tidak terpengaruh karena dihitung dari ledger kas_total.
        if ((int) ($payment->Utang_Anggota ?? 0) > 0) {
            $payment->Status_Pembayaran = 1;
        }
        $payment->Keterangan = $validated['description'] ?? 'Utang dicatat oleh admin';
        $payment->save();

        $this->simpanLogAktivitas([
            'title' => 'Utang dicatat',
            'description' => 'Utang ditambahkan sebesar Rp ' . number_format($validated['amount'], 0, ',', '.'),
            'amount' => $validated['amount'],
            'direction' => 'utang',
            'user_nim' => $validated['nim'],
        ]);

        return back()->with('success', 'Utang berhasil dicatat.');
    }

    public function approvePayment($nim)
    {
        $payment = grubkas::with('datasikad')->where('Nim_key', $nim)->firstOrFail();

        // Verifikasi order QRIS ke temanqris kalau pembayaran lewat QRIS
        if (!empty($payment->order_id)) {
            $this->verifikasiQrisOrder(
                $payment->order_id,
                $payment->datasikad?->nama ?? $nim
            );
        }

        // Kas hanya ditambah saat transisi dari belum-sudah (hindari double approve)
        $sebelumnyaSudah = (int) $payment->Status_Pembayaran === 3;

        // Simpan nominal sebelum dialokasikan, supaya kas dihitung dari
        // nilai asli yang dibayar, bukan sisa utang setelah alokasi.
        $nominal = (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih));

        $payment->Status_Pembayaran = 3;
        $payment->Tanggal_Pembayaran = now()->toDateString();

        if (!$sebelumnyaSudah) {
            // Kelebihan pembayaran otomatis jadi saldo lebih anggota
            $this->alokasikanPembayaran($payment, $nominal);
            GrubkasDashboard::tambahKas('in', $nominal);
        }

        $payment->save();

        if (!$sebelumnyaSudah) {
            $this->simpanLogAktivitas([
                'title' => 'Pembayaran dikonfirmasi',
                'description' => 'Pembayaran dari NIM ' . $nim . ' telah dikonfirmasi',
                'amount' => $nominal,
                'direction' => 'in',
                'user_nim' => $nim,
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    /**
     * Alokasikan nominal pembayaran ke utang anggota.
     * Kalau bayar lebih dari utang, kelebihannya otomatis menjadi
     * saldo lebih yang bisa dipakai untuk utang berikutnya.
     */
    private function alokasikanPembayaran(grubkas $payment, int $nominal): void
    {
        if ($nominal <= 0) {
            return;
        }

        $utang = (int) ($payment->Utang_Anggota ?? 0);
        $saldo = (int) ($payment->Saldo_Lebih ?? 0);

        if ($nominal >= $utang) {
            $payment->Saldo_Lebih = $saldo + ($nominal - $utang);
            $payment->Utang_Anggota = 0;
        } else {
            $payment->Utang_Anggota = $utang - $nominal;
        }
    }

    /**
     * Pakai saldo lebih untuk menutup utang yang ada.
     * Kalau saldo lebih cukup, utang lunas dan sisanya tetap saldo lebih.
     * Kalau kurang, utang berkurang dan saldo lebih habis.
     */
    private function terapkanSaldoLebih(grubkas $payment): void
    {
        $utang = (int) ($payment->Utang_Anggota ?? 0);
        $saldo = (int) ($payment->Saldo_Lebih ?? 0);

        if ($utang <= 0 || $saldo <= 0) {
            return;
        }

        if ($saldo >= $utang) {
            $payment->Saldo_Lebih = $saldo - $utang;
            $payment->Utang_Anggota = 0;
        } else {
            $payment->Utang_Anggota = $utang - $saldo;
            $payment->Saldo_Lebih = 0;
        }
    }

    /**
     * Verifikasi order pembayaran QRIS ke https://temanqris.com.
     * Gagal verifikasi tidak menghentikan konfirmasi lokal, karena
     * admin sudah memeriksa bukti secara manual.
     */
    private function verifikasiQrisOrder(string $orderId, string $payerName): bool
    {
        $apiKey = config('services.temanqris.apikey');

        if (!$apiKey) {
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-API-KEY' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->post('https://temanqris.com/api/qris/orders/' . $orderId . '/verify', [
                    'payer_name' => $payerName,
                    'payer_note' => 'Pembayaran dikonfirmasi oleh admin',
                ]);

            return $response->successful() && !empty($response->json('success'));
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function rejectPayment(Request $request, $nim)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'nullable|string',
        ]);

        $payment = grubkas::where('Nim_key', $nim)->firstOrFail();
        $payment->Status_Pembayaran = 4;
        $payment->Keterangan = $validated['alasan_penolakan'] ?? 'Ditolak oleh admin';
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

        GrubkasDashboard::resetKas();

        Storage::disk('public')->deleteDirectory('bukti_pembayaran');
        Storage::disk('public')->makeDirectory('bukti_pembayaran');

        return back()->with('success', 'Semua data pembayaran berhasil direset.');
    }

    private function ambilSettingFinance(): array
    {
        return GrubkasDashboard::settingFinance();
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
