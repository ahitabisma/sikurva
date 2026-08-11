<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PatientSharedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $sharedBy;
    public $patient;
    public $shared;
    public $shareStatus;

    public function __construct($sharedBy, $patient, $shared)
    {
        $this->sharedBy = $sharedBy;
        $this->patient = $patient;
        $this->shared = $shared;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    public function toDatabase($notifiable)
    {
        return [
            'shared_id' => $this->shared->id,
            'type' => 'patient_shared',
            'title' => 'Pasien Dibagikan',
            'message' => "{$this->sharedBy->name} membagikan pasien {$this->patient->nama} kepada Anda.",
            'patient_id' => $this->patient->id,
            'shared_by_id' => $this->sharedBy->id,
            'share_status' => $this->shared->status,
        ];
    }
}
