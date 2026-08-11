<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurvaTableSetting extends Model
{
    protected $table = 'kurva_table_settings';

    protected $fillable = [
        'nama_tabel',
        'nama',
        'judul',
        'ket_y',
        'y_min',
        'y_max',
        'y_minor',
        'y_mayor',
        'y_unit',
        'ket_x',
        'x_min',
        'x_max',
        'x_minor',
        'x_mayor',
        'x_unit',
        'sumbu_y',
        'sumbu_x',
    ];

    public const TABLE_COLUMNS = [
        'table1' => 'day',
        'table2' => 'day',
        'table3' => 'day',
        'table4' => 'length',
        'table5' => 'day',
        'table6' => 'month',
        'table7' => 'month',
        'table8' => 'month',
    ];

    public const TABLE_COLUMNS_IG = [
        'table9' => 'week',
        'table10' => 'week',
        'table11' => 'week',
        'table12' => 'length',
    ];
}
