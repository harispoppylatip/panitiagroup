<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grubkas extends Model
{
    protected $table = 'grubkas_info';
    protected $fillable = ['Nim_key', 'Saldo_Lebih', 'Utang_Anggota', 'Nominal_Bayar', 'Tanggal_Pembayaran', 'Keterangan', 'Bukti_Pembayaran', 'Status_Pembayaran', 'order_id', 'link_code'];

    public function datasikad(){
       return $this->belongsTo(Datasikadmodel::class, 'Nim_key', 'Nim');
    }

    public function Status(){
        return $this->belongsTo(StatusPembayaranModel::class, 'Status_Pembayaran', 'Status_id');
    }

    /**
     * Sisa utang setelah saldo lebih dipakai untuk membayar utang.
     * Saldo lebih otomatis mengurangi utang; sisanya yang harus dibayar.
     */
    public function getSisaUtangAttribute(): int
    {
        return max(0, (int) ($this->Utang_Anggota ?? 0) - (int) ($this->Saldo_Lebih ?? 0));
    }
}

