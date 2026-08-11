<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CollaboratorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $sharedBy;
    public $shared;

    public function __construct($sharedBy, $shared)
    {
        $this->sharedBy = $sharedBy;
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
            'type' => 'collaborator_shared',
            'title' => 'Permintaan Kolaborasi',
            'message' => "{$this->sharedBy->name} mengundang Anda untuk menjadi kolaborator.",
            'shared_by_id' => $this->sharedBy->id,
            'share_status' => $this->shared->status,
        ];
    }
}
