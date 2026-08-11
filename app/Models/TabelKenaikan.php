<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelKenaikan extends Model
{
    protected $table = 'tabel_kenaikans';

    protected $fillable = [
        'usia_bulan',
        'jenis_kelamin',
        'bb_bawah',
        'bb_atas',
        'bb_unit',
        'tb_bawah',
        'tb_atas',
        'tb_unit',
        'lk_bawah',
        'lk_atas',
        'lk_unit',
    ];
}
