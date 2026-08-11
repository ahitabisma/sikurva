<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendKurvaMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaPenerima;
    public string $namaPatient;
    public string $fileName;
    public string $senderName;

    public function __construct(string $senderName, string $namaPenerima, string $namaPatient, string $fileName)
    {
        $this->namaPenerima = $namaPenerima;
        $this->namaPatient = $namaPatient;
        $this->fileName = $fileName;
        $this->senderName = $senderName;
    }

    public function build()
    {
        return $this->from('info@ekurva.com', $this->senderName)
            ->subject('Laporan Grafik Kurva')
            ->view('email.kurva')
            ->with(['namaPenerima' => $this->namaPenerima, 'namaPatient' => $this->namaPatient])
            ->attach(Storage::disk('public')->path("kurva/{$this->fileName}"));
    }
}
