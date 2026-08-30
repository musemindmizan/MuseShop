<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'store_name',
        'currency',
        'status',
        'notification_email',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
