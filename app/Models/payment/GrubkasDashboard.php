<?php

namespace App\Models\payment;

use Illuminate\Database\Eloquent\Model;

class GrubkasDashboard extends Model
{
    protected $table = 'grubkas_dashboard';
    protected $fillable = ['key', 'value', 'Iuran_Perminggu', 'Total_Saldo', 'Total_Masuk', 'Total_Keluar', 'Jumlah_belum_bayar', 'Jumlah_Sudah_bayar'];

    /**
     * Total kas disimpan sebagai key-value 'kas_total' (json {in, out}).
     * Tabel log aktivitas lama tidak dipakai lagi, jadi kas tidak boleh
     * bergantung pada status pembayaran anggota (status bisa di-reset).
     */
    public static function totalKas(): array
    {
        $row = static::where('key', 'kas_total')->first();

        if ($row && $row->value) {
            $decoded = json_decode($row->value, true);
            if (is_array($decoded)) {
                return [
                    'in' => (int) ($decoded['in'] ?? 0),
                    'out' => (int) ($decoded['out'] ?? 0),
                ];
            }
        }

        // Migrasi data lama: hitung sekali dari pembayaran berstatus Sudah Bayar
        $in = \App\Models\grubkas::query()
            ->where('Status_Pembayaran', 3)
            ->get()
            ->sum(fn ($p) => (int) ($p->Nominal_Bayar ?: ((int) $p->Utang_Anggota + (int) $p->Saldo_Lebih)));

        $kas = ['in' => $in, 'out' => 0];
        static::simpanKas($kas);

        return $kas;
    }

    public static function tambahKas(string $arah, int $jumlah): void
    {
        $kas = static::totalKas();
        $kas[$arah] += $jumlah;
        static::simpanKas($kas);
    }

    /**
     * Pengaturan keuangan (iuran mingguan) yang diisi admin di /admin/finance.
     * Satu sumber untuk halaman admin dan command tagihan mingguan.
     */
    public static function settingFinance(): array
    {
        $row = static::where('key', 'finance_settings')->first();
        $decoded = $row && $row->value ? json_decode($row->value, true) : null;

        return array_merge(['weekly_fee' => 10000], is_array($decoded) ? $decoded : []);
    }

    public static function simpanSettingFinance(array $settings): void
    {
        static::updateOrCreate(
            ['key' => 'finance_settings'],
            // Kolom lain NOT NULL tanpa default, isi 0 supaya baris baru bisa dibuat
            ['value' => json_encode($settings), 'Iuran_Perminggu' => (int) ($settings['weekly_fee'] ?? 0),
                'Total_Saldo' => 0, 'Total_Masuk' => 0, 'Total_Keluar' => 0,
                'Jumlah_belum_bayar' => 0, 'Jumlah_Sudah_bayar' => 0]
        );
    }

    public static function resetKas(): void
    {
        static::where('key', 'kas_total')->delete();
    }

    private static function simpanKas(array $kas): void
    {
        static::updateOrCreate(
            ['key' => 'kas_total'],
            [
                'value' => json_encode($kas),
                // Kolom lain NOT NULL tanpa default di tabel ini, isi dengan 0
                'Iuran_Perminggu' => 0,
                'Total_Saldo' => (int) ($kas['in'] - $kas['out']),
                'Total_Masuk' => (int) $kas['in'],
                'Total_Keluar' => (int) $kas['out'],
                'Jumlah_belum_bayar' => 0,
                'Jumlah_Sudah_bayar' => 0,
            ]
        );
    }
}
