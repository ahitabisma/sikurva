<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpHelp extends Model
{
    protected $table = 'lp_helps';

    protected $fillable = [
        'title',
        'url',
    ];
}
