<?php

namespace App\Jobs;

use App\Http\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendPdfWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $patient;
    protected $filename;
    protected $whatsappNumber;
    protected $fileUrl;
    protected $images;
    protected $whatsappService;

    /**
     * Create a new job instance.
     */
    public function __construct($patient, $filename, $whatsappNumber, $fileUrl, $images = [])
    {
        $this->patient = $patient;
        $this->filename = $filename;
        $this->whatsappNumber = $whatsappNumber;
        $this->fileUrl = $fileUrl;
        $this->images = $images;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsappService $whatsappService): void
    {
        try {
            // First, send welcome message
            $whatsappService->sendTextMessage(
                $this->whatsappNumber,
                "Halo,\n\nBerikut ini kami kirimkan laporan kurva pertumbuhan untuk *{$this->patient->nama}*.\n\nMohon tunggu sebentar, file PDF sedang dikirim...\n\nTerima kasih telah menggunakan layanan kami.\n\n\n*Powered by Ekurva.com*"
            );

            // Send PDF
            $sendPdf = $whatsappService->sendDocument(
                $this->whatsappNumber,
                $this->fileUrl,
                "Laporan Kurva Pertumbuhan untuk pasien {$this->patient->nama}"
            );

            $success = $sendPdf['result'] == 'true' ? true : false;

            Log::info('Download Url : ', ['url' => $this->fileUrl]);

            // If the file couldn't be sent, send a download link
            if (!$success) {
                $whatsappService->sendTextMessage(
                    $this->whatsappNumber,
                    "Dokumen kurva pertumbuhan untuk pasien {$this->patient->nama} telah siap. Silakan unduh melalui link berikut:\n\n{$this->fileUrl}\n\n(Link ini akan aktif selama 48 jam)"
                );
            }

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

            Log::info("Job WhatsApp PDF delivery process completed for {$this->whatsappNumber}");
        } catch (\Exception $e) {
            Log::error("Error dalam job SendPdfWhatsapp: " . $e->getMessage());
            throw $e;
        }
    }
}
