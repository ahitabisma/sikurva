<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupPdfFiles extends Command
{
    protected $signature = 'pdf:cleanup {--days=2 : Number of days to keep files}';
    protected $description = 'Clean up old PDF files from storage/app/public/kurva directory';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        $deletedCount = 0;
        $directory = 'kurva';

        $this->info("Cleaning up PDF files older than {$days} days from storage/app/public/kurva directory");

        $files = Storage::disk('public')->files($directory);

        foreach ($files as $file) {
            if (!str_ends_with(strtolower($file), '.pdf')) {
                continue;
            }

            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

            if ($lastModified->lt($cutoffDate)) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                Log::info("Deleted old PDF file: {$file}");
            }
        }

        $this->info("Successfully deleted {$deletedCount} old PDF files");
        Log::info("PDF cleanup completed: {$deletedCount} files deleted");

        return 0;
    }
}
