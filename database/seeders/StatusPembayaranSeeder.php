<?php

namespace Database\Seeders;

use App\Models\StatusPembayaranModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusPembayaranModel::create([
            'Status_id' => 1,
            'Status' => 'Belum Bayar'
        ]);
        StatusPembayaranModel::create([
            'Status_id' => 2,
            'Status' => 'Menunggu Konfirmasi'
        ]);
        StatusPembayaranModel::create([
            'Status_id' => 3,
            'Status' => 'Sudah Bayar'
        ]);
    }
}
