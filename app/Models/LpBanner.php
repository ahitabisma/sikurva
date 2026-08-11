<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LpBanner extends Model
{
    protected $table = 'lp_banners';

    protected $fillable = [
        'bg_banner',
        'title',
        'subtitle',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('lp_banners'); // Bersihkan cache saat ada update atau insert
        });

        static::deleted(function () {
            Cache::forget('lp_banners'); // Bersihkan cache saat ada delete
        });
    }
}
