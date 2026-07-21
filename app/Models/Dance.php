<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'description',
        'region',
        'origin',
        'cultural_meaning',
        'historical_background',
        'video_url',
        'image_path',
    ];

    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }
        // Handles: youtube.com/watch?v=, youtu.be/, youtube.com/shorts/, youtube.com/embed/
        preg_match(
            '/(?:v=|youtu\.be\/|shorts\/|embed\/)([a-zA-Z0-9_-]{11})/',
            $this->video_url,
            $m
        );
        return isset($m[1]) ? "https://www.youtube.com/embed/{$m[1]}" : null;
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
