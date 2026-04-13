<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gambaran extends Model
{
    protected $table = 'gambar';
    protected $fillable = ['gambar', 'detail'];
}
