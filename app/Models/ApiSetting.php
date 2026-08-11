<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    protected $table = 'api_settings';

    protected $fillable = [
        'key',
        'value',
        'is_encrypted',
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
