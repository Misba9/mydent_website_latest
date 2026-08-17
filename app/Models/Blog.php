<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'thumbnail',
        'issue_type',
        'issue_level',
        'treatment_time',
        'aligner_count',
        'content',
    ];

    public function getThumbnailUrlAttribute(): string
    {
        return getMediaUrl($this->thumbnail, 'assets/image/mydent-logo.png');
    }
}
