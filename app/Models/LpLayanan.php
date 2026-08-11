<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpLayanan extends Model
{
    protected $table = 'lp_layanans'; // Nama tabel di database

    protected $fillable = [
        'image',
        'title',
        'description',
    ];
}
