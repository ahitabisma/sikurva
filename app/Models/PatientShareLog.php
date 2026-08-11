<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientShareLog extends Model
{
    protected $table = 'patient_share_logs';

    protected $fillable = [
        'user_id',         // siapa yang melakukan aksi
        'target_user_id',  // target share (user lain)
        'patient_id',      // opsional: hanya untuk share pasien (non-nakes)
        'antro_patient_id',
        'action',          // 'share', 'unshare', 'accept', 'reject'
        'context',         // 'patient' atau 'collaborator'
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function antroPatient() {
        return $this->belongsTo(AntroPatient::class, 'antro_patient_id');
    }
}
