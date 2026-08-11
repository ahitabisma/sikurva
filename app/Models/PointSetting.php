<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointSetting extends Model
{
    protected $fillable = [
        'type',
        'user_type',
        'name',
        'points',
        'duration',
        'duration_type',
    ];

    // simpan nama dalam huruf kapital
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
