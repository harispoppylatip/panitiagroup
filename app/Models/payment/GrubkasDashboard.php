<?php

namespace App\Models\payment;

use Illuminate\Database\Eloquent\Model;

class GrubkasDashboard extends Model
{
    protected $table = 'grubkas_dashboard';
    protected $fillable = ['Iuran_Perminggu', 'Total_Saldo', 'Total_Masuk', 'Total_Keluar', 'Jumlah_belum_bayar', 'Jumlah_Sudah_bayar'];
}
