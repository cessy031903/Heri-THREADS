<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'municipality',
        'description',
        'region',
        'origin',
        'cultural_meaning',
        'historical_background',
        'video_url',
        'video_path',
        'image_path',
    ];

    public function getEmbedUrlAttribute(): ?string
    {
        return static::youtubeEmbedUrl($this->video_url);
    }

    /**
     * Converts a YouTube URL (watch, youtu.be, shorts, or already-embed) to
     * its embeddable form. Static so both the model and any form preview
     * (e.g. ManageDances' live-typing embed preview, before a Dance exists)
     * can share the same extraction logic instead of duplicating the regex.
     */
    public static function youtubeEmbedUrl(?string $videoUrl): ?string
    {
        if (! $videoUrl) {
            return null;
        }
        // Handles: youtube.com/watch?v=, youtu.be/, youtube.com/shorts/, youtube.com/embed/
        preg_match(
            '/(?:v=|youtu\.be\/|shorts\/|embed\/)([a-zA-Z0-9_-]{11})/',
            $videoUrl,
            $m
        );
        return isset($m[1]) ? "https://www.youtube.com/embed/{$m[1]}" : null;
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
