<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPembayaranModel extends Model
{
    protected $table = 'Status_Pembayaran';
    protected $fillable = ['Status_id', 'Status'];
}
