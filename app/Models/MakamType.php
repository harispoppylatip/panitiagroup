<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakamType extends Model
{
    protected $table = 'makam_types';

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'blok',
        'stok_tersedia',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
        'stok_tersedia' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(MakamOrder::class, 'makam_type_id');
    }
}
