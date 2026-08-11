<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Instansi extends Model
{
    protected $fillable = [
        'name',
        'referral_code',
        'is_support_header',
        'header',
        'sender_name',
        'is_verified',
    ];

    // simpan kode lokal dalam huruf kapital
    public function setKodeLokalAttribute($value)
    {
        $this->attributes['kode_lokal'] = strtoupper($value);
    }

    protected static function booted()
    {
        static::creating(function ($instansi) {
            if (!$instansi->referral_code) {
                $instansi->referral_code = strtoupper(Str::random(6));

                // Memeriksa keunikan referral_code di tabel users dan instansis
                while (
                    DB::table('users')
                    ->where('referral_code', $instansi->referral_code)
                    ->exists()
                    ||
                    DB::table('instansis')
                    ->where('referral_code', $instansi->referral_code)
                    ->exists()
                ) {
                    $instansi->referral_code = strtoupper(Str::random(6));
                }
            }
        });
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'instansi_id');
    }

    public function pointBatch(): HasMany
    {
        return $this->hasMany(PointBatch::class, 'instansi_id');
    }
}
