<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InteractiveGuide extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'municipality',
        'title',
        'instruction',
        'image_path',
    ];

    public function hotspots(): HasMany
    {
        return $this->hasMany(GuideHotspot::class)->orderBy('order');
    }
}
