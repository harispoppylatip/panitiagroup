<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Datasikadmodel extends Model
{
    protected $table = 'datasikad';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nama', 'Nim', 'access_token', 'refresh_token', 'status_onoff', 'urlpost'];
    public $timestamps = true;

    public function iuran()
    {
        return $this->hasMany(grubkas::class, 'user_nim', 'Nim');
    }

    public function latestIuran()
    {
        return $this->hasOne(grubkas::class, 'user_nim', 'Nim')->latestOfMany();
    }
}
