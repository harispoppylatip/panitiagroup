<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Datasikadmodel extends Model
{
    protected $table = 'datasikad';
    protected $fillable = ['nama', 'Nim', 'access_token', 'refresh_token', 'status_onoff', 'urlpost'];
}
