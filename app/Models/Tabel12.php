<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabel12 extends Model
{
    protected $table = 'table12';

    protected $fillable = [
        'jenis_kelamin',
        'length',
        'z3neg',
        'z2neg',
        'z1neg',
        'z0',
        'z1',
        'z2',
        'z3',
    ];
}
