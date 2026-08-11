<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = ['user_id', 'instansi_id', 'point_batch_id', 'points', 'type', 'referral_code', 'description', 'patient_id', 'point_setting_id'];
}
