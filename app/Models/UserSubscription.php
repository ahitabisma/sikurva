<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'instansi_id',
        'subscription_id',
        'price',
        'point',
        'duration',
        'duration_type',
        'status',
        'started_at',
        'expired_at',
        'payment_type',
        'snap_token',
        'snap_url',
        'payment_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
