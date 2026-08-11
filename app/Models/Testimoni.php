<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Testimoni extends Model
{
    protected $fillable = ['user_id', 'instansi_id', 'testimoni', 'rating'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('lp_testimonis');
        });

        static::deleted(function () {
            Cache::forget('lp_testimonis');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
