<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowPointsNotification extends Notification
{
    use Queueable;
    public $points;

    /**
     * Create a new notification instance.
     */
    public function __construct($points)
    {
        $this->points = $points;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'low_points',
            'title' => 'Poin Anda sudah kurang dari 100!',
            'message' => "Poin Anda saat ini {$this->points}, silahkan isi ulang poin Anda.",
            'url' => "/langganan/create",
            'shared_by_name' => config('app.name'),
        ];
    }
}
