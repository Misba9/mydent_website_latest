<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'thumbnail',
        'content'
    ];

    public function getThumbnailUrlAttribute(): string
    {
        return getMediaUrl($this->thumbnail, 'assets/image/mydent-logo.png');
    }
}
