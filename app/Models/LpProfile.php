<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpProfile extends Model
{
    protected $table = 'lp_profiles'; // Nama tabel di database

    protected $fillable = [
        'name',
        'subtitle',
        'description',
        'skills', // Disimpan dalam format JSON
        'photo',
    ];

    protected $casts = [
        'skills' => 'array', // Agar otomatis dikonversi ke array saat diakses
    ];
}
