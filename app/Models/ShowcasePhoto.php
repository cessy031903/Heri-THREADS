<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShowcasePhoto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_path',
        'label',
        'sub_label',
        'link_url',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];
}
