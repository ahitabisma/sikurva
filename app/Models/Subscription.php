<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Subscription extends Model
{
    protected $fillable = ['name', 'point', 'duration', 'price', 'status', 'description', 'duration_type'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('lp_pakets');
        });

        static::deleted(function () {
            Cache::forget('lp_pakets');
        });
    }
}
