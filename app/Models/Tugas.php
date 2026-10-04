<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    // id boleh diisi: id baris n8n Data Table dipakai langsung sebagai id web
    protected $fillable = ['id', 'namatugas', 'penjelasan', 'deadline', 'deadline_tanggal'];

    protected $casts = [
        'deadline_tanggal' => 'date:Y-m-d',
    ];
}
