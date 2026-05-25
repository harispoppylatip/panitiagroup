<?php

namespace App\Http\Controllers\payment;

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

class admingrubkas extends Controller
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
                        'time' => Carbon::parse($log->occurred_at ?? $log->created_at)->format('d M · H:i'),
                    ];
                })
                ->filter()
                ->values();
        }

        if ($activityLogs->isEmpty()) {
            $activityLogs = $payments
                ->sortByDesc('updated_at')
                ->values()
                ->map(function ($payment) {
                    $statusId = (int) $payment->Status_Pembayaran;
                    if ($statusId !== 3) {
                        return null;
                    }

                    $name = $payment->datasikad?->nama ?? 'Tanpa Nama';
                    $amount = (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih));

                    return [
                        'type' => 'in',
                        'title' => 'Pembayaran dikonfirmasi — ' . $name,
                        'detail' => 'NIM ' . $payment->Nim_key . ' · ' . ($payment->Status?->Status ?? 'Status belum tersedia'),
                        'amount' => '+Rp ' . number_format($amount, 0, ',', '.'),
                        'time' => Carbon::parse($payment->Tanggal_Pembayaran ?? $payment->updated_at ?? $payment->created_at)->format('d M · H:i'),
                    ];
                })
                ->filter()
                ->values();
        }

        $jumlahSudahBayar = $payments->where('Status_Pembayaran', 3)->count();
        $jumlahBelumBayar = $payments->where('Status_Pembayaran', 1)->count();
        $menungguKonfirmasi = $payments->where('Status_Pembayaran', 2)->count();

        $totalMasuk = $payments
            ->filter(fn ($payment) => (int) $payment->Status_Pembayaran === 3)
            ->sum(fn ($payment) => (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih)));

        $totalKeluar = Schema::hasTable('grubkas_activity_logs')
            ? (int) DB::table('grubkas_activity_logs')->where('direction', 'out')->sum('amount')
            : 0;
        $totalKas = $totalMasuk - $totalKeluar;

        $memberBalances = $payments
            ->map(function ($payment) {
                $name = $payment->datasikad?->nama ?? 'Tanpa Nama';
                return [
                    'name' => $name,
                    'nim' => $payment->Nim_key,
                    'utang' => (int) $payment->Utang_Anggota,
                    'saldo_lebih' => (int) $payment->Saldo_Lebih,
                    'status_id' => (int) $payment->Status_Pembayaran,
                ];
            })
            ->filter(fn ($member) => $member['utang'] > 0 || $member['saldo_lebih'] > 0)
            ->sortByDesc(fn ($member) => ($member['utang'] * 1000000) + $member['saldo_lebih'])
            ->values();

        $dashboard = Schema::hasTable('grubkas_dashboard')
            ? (GrubkasDashboard::query()->first() ?? new GrubkasDashboard())
            : new GrubkasDashboard();
        $dashboard->Iuran_Perminggu = $weeklyFee;
        $dashboard->Total_Saldo = $totalKas;
        $dashboard->Total_Masuk = $totalMasuk;
        $dashboard->Total_Keluar = $totalKeluar;
        $dashboard->Jumlah_belum_bayar = $jumlahBelumBayar;
        $dashboard->Jumlah_Sudah_bayar = $jumlahSudahBayar;

        if (Schema::hasTable('grubkas_dashboard')) {
            $dashboard->save();
        }

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
                'value' => $jumlahSudahBayar . ' anggota',
                'meta' => 'Berstatus lunas',
                'icon' => 'bi-people',
                'tone' => 'success',
            ],
            [
                'label' => 'Menunggu konfirmasi',
                'value' => $menungguKonfirmasi,
                'meta' => 'Perlu dicek',
                'icon' => 'bi-bell',
                'tone' => 'warning',
            ],
        ];

        $memberChoices = Datasikadmodel::query()
            ->orderBy('nama')
            ->get(['Nim', 'nama']);

        return view('admin.finance.keuangan', [
            'data' => $dashboard,
            'stats' => $stats,
            'pendingPayments' => $pendingPayments,
            'historyPayments' => $historyPayments,
            'activityLogs' => $activityLogs,
            'historyCount' => $historyPayments->count(),
            'pendingCount' => $pendingPayments->count(),
            'menungguKonfirmasi' => $menungguKonfirmasi,
            'totalKas' => $totalKas,
            'weeklyFee' => $weeklyFee,
            'financeSettings' => $settings,
            'memberChoices' => $memberChoices,
            'memberBalances' => $memberBalances,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'weekly_fee' => ['required', 'integer', 'min:1000'],
            'berlaku_mulai' => ['nullable', 'date'],
            'catatan_perubahan' => ['nullable', 'string', 'max:255'],
        ]);

        if (!Schema::hasTable('finance_settings')) {
            return back()->with('error', 'Tabel finance_settings belum tersedia.');
        }

        $sekarang = Carbon::now();
        $deskripsi = trim((string) ($data['catatan_perubahan'] ?? ''));
        $berlakuMulai = $data['berlaku_mulai'] ?? null;

        if (!empty($berlakuMulai)) {
            $deskripsi = trim($deskripsi . ' · Berlaku mulai ' . Carbon::parse($berlakuMulai)->format('d M Y'));
        }

        $row = DB::table('finance_settings')->orderBy('id')->first();

        if ($row) {
            DB::table('finance_settings')
                ->where('id', $row->id)
                ->update([
                    'weekly_fee' => $data['weekly_fee'],
                    'default_weekly_description' => $deskripsi ?: null,
                    'updated_at' => $sekarang,
                ]);
        } else {
            DB::table('finance_settings')->insert([
                'weekly_fee' => $data['weekly_fee'],
                'default_weekly_description' => $deskripsi ?: null,
                'created_at' => $sekarang,
                'updated_at' => $sekarang,
            ]);
        }

        $this->simpanLogAktivitas([
            'activity_type' => 'setting',
            'direction' => 'set',
            'amount' => $data['weekly_fee'],
            'title' => 'Iuran mingguan diperbarui',
            'description' => $deskripsi ?: 'Perubahan nominal dari admin',
            'transaction_status' => 'updated',
        ]);

        return back()->with('success', 'Iuran mingguan berhasil diperbarui.');
    }

    public function storeManualCash(Request $request)
    {
        $data = $request->validate([
            'nim' => ['required', 'exists:datasikad,Nim'],
            'amount' => ['required', 'integer', 'min:1'],
            'tanggal_pembayaran' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $anggota = Datasikadmodel::where('Nim', $data['nim'])->first();
        $record = grubkas::firstOrNew(['Nim_key' => $data['nim']]);
        $utangAwal = (int) ($record->Utang_Anggota ?? 0);
        $saldoAwal = (int) ($record->Saldo_Lebih ?? 0);
        $bayar = (int) $data['amount'];
        $sisaUtang = $utangAwal - $bayar;

        if ($sisaUtang > 0) {
            $utangBaru = $sisaUtang;
            $saldoBaru = $saldoAwal;
            $statusBaru = 1;
        } else {
            $utangBaru = 0;
            $saldoBaru = $saldoAwal + abs($sisaUtang);
            $statusBaru = 3;
        }

        $record->fill([
            'Utang_Anggota' => $utangBaru,
            'Saldo_Lebih' => $saldoBaru,
            'Nominal_Bayar' => $bayar,
            'Tanggal_Pembayaran' => Carbon::parse($data['tanggal_pembayaran'])->toDateString(),
            'Keterangan' => $data['keterangan'] ?: 'Pembayaran cash manual',
            'Bukti_Pembayaran' => $record->Bukti_Pembayaran,
            'Status_Pembayaran' => $statusBaru,
        ]);
        $record->save();

        $this->simpanLogAktivitas([
            'user_nim' => $data['nim'],
            'user_name' => $anggota?->nama,
            'activity_type' => 'payment',
            'direction' => 'in',
            'amount' => $bayar,
            'title' => 'Pembayaran cash manual',
            'description' => trim(($anggota?->nama ?? 'Tanpa Nama') . ' · ' . ($data['keterangan'] ?? 'dicatat manual')),
            'transaction_status' => $statusBaru === 3 ? 'paid' : 'partial',
            'occurred_at' => Carbon::parse($data['tanggal_pembayaran']),
        ]);

        return back()->with('success', 'Pembayaran cash manual berhasil disimpan.');
    }

    public function storeManualDebt(Request $request)
    {
        $data = $request->validate([
            'nim' => ['required', 'exists:datasikad,Nim'],
            'amount' => ['required', 'integer', 'min:1'],
            'tanggal_pembayaran' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $anggota = Datasikadmodel::where('Nim', $data['nim'])->first();
        $record = grubkas::firstOrNew(['Nim_key' => $data['nim']]);
        $utangAwal = (int) ($record->Utang_Anggota ?? 0);
        $utangBaru = $utangAwal + (int) $data['amount'];

        $record->fill([
            'Utang_Anggota' => $utangBaru,
            'Saldo_Lebih' => (int) ($record->Saldo_Lebih ?? 0),
            'Nominal_Bayar' => 0,
            'Tanggal_Pembayaran' => Carbon::parse($data['tanggal_pembayaran'])->toDateString(),
            'Keterangan' => $data['keterangan'] ?: 'Utang manual ditambahkan',
            'Bukti_Pembayaran' => $record->Bukti_Pembayaran,
            'Status_Pembayaran' => 1,
        ]);
        $record->save();

        $this->simpanLogAktivitas([
            'user_nim' => $data['nim'],
            'user_name' => $anggota?->nama,
            'activity_type' => 'debt',
            'direction' => 'set',
            'amount' => (int) $data['amount'],
            'title' => 'Utang manual ditambahkan',
            'description' => trim(($anggota?->nama ?? 'Tanpa Nama') . ' · ' . ($data['keterangan'] ?? 'dengan input admin')),
            'transaction_status' => 'debt_added',
            'occurred_at' => Carbon::parse($data['tanggal_pembayaran']),
        ]);

        return back()->with('success', 'Utang manual berhasil ditambahkan.');
    }

    public function approvePayment(string $nim)
    {
        $record = grubkas::where('Nim_key', $nim)->first();

        if (!$record) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $anggota = $record->datasikad;
        $bayar = (int) ($record->Nominal_Bayar ?: ((int) $record->Utang_Anggota + (int) $record->Saldo_Lebih));
        $utangAwal = (int) $record->Utang_Anggota;
        $saldoAwal = (int) $record->Saldo_Lebih;
        $sisaUtang = $utangAwal - $bayar;

        if ($sisaUtang > 0) {
            $utangBaru = $sisaUtang;
            $saldoBaru = $saldoAwal;
            $statusBaru = 1;
        } else {
            $utangBaru = 0;
            $saldoBaru = $saldoAwal + abs($sisaUtang);
            $statusBaru = 3;
        }

        $record->fill([
            'Utang_Anggota' => $utangBaru,
            'Saldo_Lebih' => $saldoBaru,
            'Nominal_Bayar' => $bayar,
            'Tanggal_Pembayaran' => $record->Tanggal_Pembayaran ?? Carbon::now()->toDateString(),
            'Status_Pembayaran' => $statusBaru,
            'Keterangan' => $record->Keterangan ?: 'Pembayaran dikonfirmasi admin',
        ]);
        $record->save();

        $this->simpanLogAktivitas([
            'user_nim' => $nim,
            'user_name' => $anggota?->nama,
            'activity_type' => 'payment',
            'direction' => 'in',
            'amount' => $bayar,
            'title' => 'Pembayaran dikonfirmasi',
            'description' => ($anggota?->nama ?? 'Tanpa Nama') . ' sudah diverifikasi oleh admin',
            'transaction_status' => 'confirmed',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function rejectPayment(Request $request, string $nim)
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:255'],
        ]);

        $record = grubkas::where('Nim_key', $nim)->first();

        if (!$record) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $anggota = $record->datasikad;

        $record->fill([
            'Status_Pembayaran' => 4,
            'Keterangan' => 'Ditolak admin: ' . $validated['alasan_penolakan'],
        ]);
        $record->save();

        $this->simpanLogAktivitas([
            'user_nim' => $nim,
            'user_name' => $anggota?->nama,
            'activity_type' => 'payment',
            'direction' => 'out',
            'amount' => (int) ($record->Nominal_Bayar ?: 0),
            'title' => 'Pembayaran ditolak',
            'description' => ($anggota?->nama ?? 'Tanpa Nama') . ' ditolak: ' . $validated['alasan_penolakan'],
            'transaction_status' => 'rejected',
        ]);

        return back()->with('success', 'Pembayaran ditolak dan dikeluarkan dari antrian konfirmasi.');
    }

    public function resetAll(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'admin_password' => ['required', 'string'],
            'confirm_text' => ['required', 'string', 'in:RESET'],
        ]);

        if (!Hash::check($data['admin_password'], $user->password)) {
            return back()->with('error', 'Password admin tidak cocok.');
        }

        $fileWarnings = [];

        foreach (['bukti_pembayaran'] as $directory) {
            if (Storage::disk('public')->exists($directory)) {
                if (Storage::disk('public')->deleteDirectory($directory)) {
                } else {
                    $fileWarnings[] = 'Gagal menghapus folder ' . $directory;
                }
            }
        }

        Schema::disableForeignKeyConstraints();

        try {
            foreach (['grubkas_info', 'grubkas_activity_logs', 'finance_settings', 'grubkas_dashboard'] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $message = 'Reset grubkas selesai.';

        if (!empty($fileWarnings)) {
            $message .= ' Namun ada peringatan: ' . implode(' · ', $fileWarnings);
        }

        return back()->with('success', $message);
    }

    private function ambilSettingFinance(): array
    {
        if (!Schema::hasTable('finance_settings')) {
            return [
                'weekly_fee' => 10000,
                'default_weekly_description' => null,
            ];
        }

        $settings = DB::table('finance_settings')->orderBy('id')->first();

        return [
            'weekly_fee' => $settings?->weekly_fee ?? 10000,
            'default_weekly_description' => $settings?->default_weekly_description,
        ];
    }

    private function simpanLogAktivitas(array $data): void
    {
        if (!Schema::hasTable('grubkas_activity_logs')) {
            return;
        }

        DB::table('grubkas_activity_logs')->insert([
            'user_nim' => $data['user_nim'] ?? null,
            'user_name' => $data['user_name'] ?? null,
            'activity_type' => $data['activity_type'] ?? 'payment',
            'direction' => $data['direction'] ?? 'set',
            'amount' => (int) ($data['amount'] ?? 0),
            'title' => $data['title'] ?? '-',
            'description' => $data['description'] ?? null,
            'order_id' => $data['order_id'] ?? null,
            'transaction_status' => $data['transaction_status'] ?? null,
            'proof_path' => $data['proof_path'] ?? null,
            'proof_name' => $data['proof_name'] ?? null,
            'occurred_at' => ($data['occurred_at'] ?? Carbon::now()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
