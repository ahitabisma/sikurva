<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $referralCode;
    public $recipientEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $sender, $referralCode, $recipientEmail)
    {
        $this->sender = $sender;
        $this->referralCode = $referralCode;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $registrationUrl = route('register', [
            'referral' => $this->referralCode,
            'email' => $this->recipientEmail
        ]);

        return $this->subject('Undangan untuk Bergabung di eKurva.com')
            ->view('email.referral')
            ->with([
                'senderName' => $this->sender->name,
                'registrationUrl' => $registrationUrl
            ]);
    }
}
