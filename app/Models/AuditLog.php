<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
        'resource_id',
        'resource_name',
    ];

    public static function record(string $action, string $type, int $id, string $name): void
    {
        static::create([
            'user_id'       => auth()->id(),
            'action'        => $action,
            'resource_type' => $type,
            'resource_id'   => $id,
            'resource_name' => $name,
        ]);
    }
}
