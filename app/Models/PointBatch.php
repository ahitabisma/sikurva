<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PointBatch extends Model
{
    protected $fillable = ['user_id', 'instansi_id', 'user_subscription_id', 'points', 'remaining_points', 'type', 'expired_at'];

    protected static function booted()
    {
        static::created(function ($pointBatch) {
            self::forgetPointCache($pointBatch);
        });

        static::updated(function ($pointBatch) {
            self::forgetPointCache($pointBatch);
        });

        static::deleted(function ($pointBatch) {
            self::forgetPointCache($pointBatch);
        });
    }

    protected static function forgetPointCache(PointBatch $pointBatch)
    {
        if ($pointBatch->instansi_id) {
            Cache::forget('total_poin_instansi_' . $pointBatch->instansi_id);
        } else {
            Cache::forget('total_poin_user_' . $pointBatch->user_id);
        }
    }
}
