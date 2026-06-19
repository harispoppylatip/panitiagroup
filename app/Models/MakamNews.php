<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakamNews extends Model
{
    protected $table = 'makam_news';

    protected $fillable = [
        'title',
        'content',
        'image_url',
        'author',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
