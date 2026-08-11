<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpSetting extends Model
{
    protected $table = 'lp_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = true;

    // Optional: ambil value by key
    public static function getValue($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
