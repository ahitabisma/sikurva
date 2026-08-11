<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NakesCollaborator extends Model
{
    protected $fillable = [
        'user_id',          // user nakes yang membagikan
        'collaborator_id',  // user lain yang diajak kolaborasi
        'status',
        'accepted_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }
}
