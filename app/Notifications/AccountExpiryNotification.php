<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountExpiryNotification extends Notification
{
    use Queueable;

    protected $daysRemaining;
    protected $expiryDate;

    public function __construct($daysRemaining, $expiryDate)
    {
        $this->daysRemaining = $daysRemaining;
        $this->expiryDate = $expiryDate;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $formattedDate = Carbon::parse($this->expiryDate)->format('d M Y');

        return [
            'type' => 'account_expiry',
            'title' => 'Peringatan Masa Aktif Akun Anda',
            'message' => "Masa aktif akun Anda akan habis dalam {$this->daysRemaining} hari di tanggal {$formattedDate}. Silahkan isi ulang poin Anda agar akun Anda tetap aktif.",
            'url' => '/langganan/create',
            'shared_by_name' => config('app.name'),
            'days_remaining' => $this->daysRemaining,
            'expiry_date' => $this->expiryDate,
        ];
    }
}
