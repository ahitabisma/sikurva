<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfSetting extends Model
{
    protected $table = 'pdf_settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
