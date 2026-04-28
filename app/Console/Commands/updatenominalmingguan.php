<?php

namespace App\Console\Commands;

use App\Models\Datasikadmodel;
use App\Models\FinanceSetting;
use Illuminate\Console\Command;

class updatenominalmingguan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:updatenominalmingguan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tambah iuran mingguan dan pakai saldo lebih otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = Datasikadmodel::get();
        $nominalMingguan = FinanceSetting::weeklyFee();
        $updated = 0;
        $created = 0;

        foreach ($data as $item) {
            $iuranTerakhir = $item->iuran()
                ->latest('id')
                ->first();

            if (!$iuranTerakhir) {
                $item->iuran()->create([
                    'Nominal' => $nominalMingguan,
                    'Status_Bayar' => 0,
                    'Saldo_Lebih' => 0,
                    'Keterangan' => 'Iuran mingguan awal',
                ]);
                $created++;
            } else {
                $saldoLebih = (int) $iuranTerakhir->Saldo_Lebih;
                $nominalTambahan = $nominalMingguan;

                if ($saldoLebih >= $nominalMingguan) {
                    $saldoLebihBaru = $saldoLebih - $nominalMingguan;
                    $nominalTambahan = 0;
                } else {
                    $nominalTambahan = $nominalMingguan - $saldoLebih;
                    $saldoLebihBaru = 0;
                }

                $nominalBaru = ((int) $iuranTerakhir->Nominal) + $nominalTambahan;

                $iuranTerakhir->update([
                    'Nominal' => $nominalBaru,
                    'Saldo_Lebih' => $saldoLebihBaru,
                    'Status_Bayar' => $nominalBaru === 0 ? 1 : 0,
                ]);
                $updated++;
            }
        }

        $this->info("Selesai. updated={$updated}, created={$created}");
        return self::SUCCESS;
    }
}
