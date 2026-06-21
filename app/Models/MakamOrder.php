<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakamOrder extends Model
{
    protected $table = 'makamorders';

    protected $fillable = [
        'kode_pesanan',
        'makam_type_id',
        'nama_customer',
        'email_customer',
        'no_wa_customer',
        'alamat_customer',
        'jumlah',
        'total_harga',
        'status',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    public function makamType()
    {
        return $this->belongsTo(MakamType::class, 'makam_type_id');
    }
}
