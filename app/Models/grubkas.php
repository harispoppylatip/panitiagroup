<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grubkas extends Model
{
    protected $table = 'grubkas_info';
    protected $fillable = ['Nim_key', 'Saldo_Lebih', 'Utang_Anggota', 'Nominal_Bayar', 'Tanggal_Pembayaran', 'Keterangan', 'Bukti_Pembayaran', 'Status_Pembayaran'];

    public function datasikad(){
       return $this->belongsTo(Datasikadmodel::class, 'Nim_key', 'Nim');
    }

    public function Status(){
        return $this->belongsTo(StatusPembayaranModel::class, 'Status_Pembayaran', 'Status_id');
    }
}

