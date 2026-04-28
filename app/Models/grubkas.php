<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grubkas extends Model
{
    protected $table = 'grubkas';
    protected $fillable = ['user_nim', 'Keterangan', 'Nominal', 'Status_Bayar', 'Saldo_Lebih'];

    public function user()
    {
        return $this->belongsTo(Datasikadmodel::class, 'user_nim', 'Nim');
    }
}
