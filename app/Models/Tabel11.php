<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabel11 extends Model
{
    protected $table = 'table11';

    protected $fillable = [
        'jenis_kelamin',
        'days',
        'week',
        'z3neg',
        'z2neg',
        'z1neg',
        'z0',
        'z1',
        'z2',
        'z3',
    ];
}
