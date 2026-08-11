<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeletionWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $daysRemaining;

    public function __construct($daysRemaining)
    {
        $this->daysRemaining = $daysRemaining;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Send both in-app and email notification
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Warning: Your Account Will Be Deleted Soon')
            ->line('Your account will be automatically deleted in ' . $this->daysRemaining . ' days due to inactivity.')
            ->line('To keep your account, please purchase points before the deadline.')
            ->action('Buy Points', url('/langganan/create'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'account_deletion_warning',
            'title' => 'Peringatan Penghapusan Akun',
            'message' => "Akun Anda akan terhapus secara otomatis beserta data Anda dalam {$this->daysRemaining} hari ke depan dikarenakan tidak ada pembelian point selama 4 bulan. Silahkan beli poin untuk memperpanjang masa aktif akun Anda.",
            'url' => '/langganan/create',
            'shared_by_name' => config('app.name'),
            'days_remaining' => $this->daysRemaining,
        ];
    }
}
