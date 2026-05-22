<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grubkas extends Model
{
    protected $table = 'grubkas_info';
    protected $fillable = ['Nim_Key', 'Total_Saldo', 'Utang_Anggota', 'Keterangan'];

    public function datasikad(){
       return $this->belongsTo(Datasikadmodel::class, 'Nim_key', 'Nim');
    }
}

