<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideHotspot extends Model
{
    protected $fillable = [
        'interactive_guide_id',
        'order',
        'label',
        'description',
        'pos_x',
        'pos_y',
        'attire_id',
    ];

    protected $casts = [
        'pos_x' => 'float',
        'pos_y' => 'float',
        'order' => 'integer',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(InteractiveGuide::class, 'interactive_guide_id');
    }

    public function attire(): BelongsTo
    {
        return $this->belongsTo(Attire::class);
    }
}
