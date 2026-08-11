<?php

namespace App\Jobs;

use App\Mail\SendKurvaMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendPdfEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $patient;
    protected $filename;
    protected $senderName;
    protected $recipientEmail;
    protected $recipientName;
    protected $images;

    /**
     * Create a new job instance.
     */
    public function __construct($patient, $filename, $senderName, $recipientEmail, $recipientName, $images = [])
    {
        $this->patient = $patient;
        $this->filename = $filename;
        $this->senderName = $senderName;
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName;
        $this->images = $images;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send email
            Mail::to($this->recipientEmail)
                ->send(new SendKurvaMail(
                    $this->senderName,
                    $this->recipientName,
                    $this->patient->nama,
                    $this->filename
                ));

            Log::info("Job Email berhasil dikirim ke {$this->recipientEmail}");

            // Delete chart images
            foreach ($this->images as $imgPath) {
                // Skip deletion if this is the ads image
                if (strpos($imgPath, 'ads_') !== false || strpos($imgPath, 'lp-setting/ads_') !== false) {
                    Log::info("Skipping deletion of ads image: {$imgPath}");
                    continue;
                }

                $fullPath = public_path($imgPath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    Log::info("Gambar {$imgPath} berhasil dihapus.");
                }
            }

            // Delete PDF file after sending
            $pathToDelete = storage_path("app/public/kurva/{$this->filename}");
            if (File::exists($pathToDelete)) {
                File::delete($pathToDelete);
                Log::info("File PDF {$this->filename} berhasil dihapus setelah dikirim.");
            }
        } catch (\Exception $e) {
            Log::error("Error dalam job SendPdfEmail: " . $e->getMessage());
            // You can implement retry logic here if needed
            throw $e;
        }
    }
}
