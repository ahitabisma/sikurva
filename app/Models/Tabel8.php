<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabel8 extends Model
{
    protected $table = 'table8';

    protected $fillable = [
        'jenis_kelamin',
        'month',
        'l',
        'm',
        's',
        'sd4neg',
        'sd3neg',
        'sd2neg',
        'sd1neg',
        'sd0',
        'sd1',
        'sd2',
        'sd3',
        'sd4',
        'stdev',
        'sd5neg',
    ];
}
