<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = ['user_id', 'instansi_id', 'point_transaction_id', 'activity'];
}
