<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'nim',
        'name',
        'role',
        'image_url',
        'order',
    ];

    public function anggota()
    {
        return $this->belongsTo(Datasikadmodel::class, 'nim', 'Nim');
    }
}
