<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NonNakesPatientShare extends Model
{
    protected $table = 'patient_shares';

    protected $fillable = [
        'patient_id',
        'shared_by', // non-nakes user ID
        'shared_to', // target user ID (nakes / non-nakes)
        'status',
        'accepted_at'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_to');
    }
}
