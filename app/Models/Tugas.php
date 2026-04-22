<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $fillable = ['judul', 'deskripsi', 'mata_kuliah', 'deadline', 'status', 'prioritas'];
}
