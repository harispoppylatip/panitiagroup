<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrubkasActivityLog extends Model
{
    protected $table = 'grubkas_activity_logs';

    protected $fillable = [
        'user_nim',
        'user_name',
        'activity_type',
        'direction',
        'amount',
        'title',
        'description',
        'order_id',
        'transaction_status',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];
}
