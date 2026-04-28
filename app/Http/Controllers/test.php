<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;

class test extends Controller
{
    public function index(){
        $nim = '2411102441045';
        $data = Datasikadmodel::where('Nim', $nim)->first();

        if (!$data) {
            echo 'User tidak ditemukan';
            return;
        }

        $jumlah = $data->iuran()
            ->where('Status_Bayar', 0)
            ->sum('Nominal');

        echo $jumlah;

        // $nim?->iuran()->create([
        //     'Nominal' => 10000,
        //     'Status_Bayar' => 1,
        // ]);
    }

    public function updatemingguan()
    {
        $data = Datasikadmodel::get();
        $nominalMingguan = 10000;
        $updated = 0;
        $created = 0;

        foreach ($data as $item) {
            $iuranTerakhir = $item->iuran()
                ->latest('id')
                ->first();

            if (!$iuranTerakhir) {
                $item->iuran()->create([
                    'TanggalMulai' => now()->toDateString(),
                    'Nominal' => $nominalMingguan,
                    'Status_Bayar' => 0,
                ]);
                $created++;
            } else {
                $iuranTerakhir->update([
                    'Nominal' => ((int) $iuranTerakhir->Nominal) + $nominalMingguan,
                ]);
                $updated++;
            }
        }

        return response()->json([
            'message' => 'Update iuran mingguan selesai',
            'updated' => $updated,
            'created' => $created,
        ]);
    }
}
