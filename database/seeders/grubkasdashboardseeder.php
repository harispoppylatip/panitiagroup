<?php

namespace Database\Seeders;

use App\Models\payment\GrubkasDashboard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class grubkasdashboardseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GrubkasDashboard::create([
            'Iuran_Perminggu' => 10000,
            'Total_Saldo' => 0,
            'Total_Masuk' => 0,
            'Total_Keluar' => 0,
            'Jumlah_belum_bayar' => 0,
            'Jumlah_Sudah_bayar' => 0,
        ]);
    }
}
