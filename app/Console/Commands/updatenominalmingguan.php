<?php

namespace App\Console\Commands;

use App\Models\Datasikadmodel;
use App\Models\grubkas;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class updatenominalmingguan extends Command
{
    protected $signature = 'app:updatenominalmingguan';

    protected $description = 'Perbarui tagihan grubkas mingguan';

    public function handle()
    {
        $setting = $this->ambilSettingFinance();
        $weeklyFee = (int) ($setting['weekly_fee'] ?? 10000);
        $deskripsi = $setting['default_weekly_description'] ?: 'Tagihan mingguan otomatis';
        $rows = Datasikadmodel::all();
        $jumlah = 0;

        foreach ($rows as $row) {
            $record = grubkas::firstOrNew(['Nim_key' => $row->Nim]);
            $utangAwal = (int) ($record->Utang_Anggota ?? 0);
            $saldoAwal = (int) ($record->Saldo_Lebih ?? 0);
            $totalTagihan = $utangAwal + $weeklyFee;
            $sisaUtang = $totalTagihan - $saldoAwal;

            if ($sisaUtang > 0) {
                $utangBaru = $sisaUtang;
                $saldoBaru = 0;
            } else {
                $utangBaru = 0;
                $saldoBaru = abs($sisaUtang);
            }

            $record->fill([
                'Utang_Anggota' => $utangBaru,
                'Saldo_Lebih' => $saldoBaru,
                'Status_Pembayaran' => (int) ($record->Status_Pembayaran ?? 1) === 2 ? 2 : 1,
                'Keterangan' => trim($deskripsi . ' · ' . Carbon::now()->format('d M Y')),
            ]);
            $record->save();
            $jumlah++;
        }

        $this->simpanLogAktivitas([
            'activity_type' => 'setting',
            'direction' => 'cal',
            'amount' => $weeklyFee * $jumlah,
            'title' => 'Tagihan mingguan otomatis dibuat',
            'description' => $jumlah . ' anggota diproses dengan iuran Rp ' . number_format($weeklyFee, 0, ',', '.'),
            'transaction_status' => 'generated',
        ]);

        $this->info('Tagihan mingguan berhasil diperbarui untuk ' . $jumlah . ' anggota.');
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
            'activity_type' => $data['activity_type'] ?? 'setting',
            'direction' => $data['direction'] ?? 'cal',
            'amount' => (int) ($data['amount'] ?? 0),
            'title' => $data['title'] ?? '-',
            'description' => $data['description'] ?? null,
            'order_id' => $data['order_id'] ?? null,
            'transaction_status' => $data['transaction_status'] ?? null,
            'proof_path' => $data['proof_path'] ?? null,
            'proof_name' => $data['proof_name'] ?? null,
            'occurred_at' => $data['occurred_at'] ?? Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}