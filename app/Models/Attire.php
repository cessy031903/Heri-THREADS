<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attire extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_general',
        'name_dialect',
        'municipality',
        'gender',
        'description',
        'material',
        'cultural_significance',
        'source_info',
        'image_path',
    ];

    public function scopeByMunicipality($query, string $municipality)
    {
        return $query->where('municipality', $municipality);
    }

    public function scopeByGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }
}
