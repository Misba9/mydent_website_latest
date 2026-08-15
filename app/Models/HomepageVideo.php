<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',         // Title of the video
        'section',       // Section identifier (e.g., section-1, section-2)
        'order',         // Display order (used in section-1)
        'video_path',    // Storage path to the video file
        'image_path'
    ];
}
