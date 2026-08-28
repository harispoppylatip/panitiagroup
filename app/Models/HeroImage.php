<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    protected $fillable = [
        'position',
        'image_url',
        'image_url_mobile',
        'alt_text',
    ];
}
