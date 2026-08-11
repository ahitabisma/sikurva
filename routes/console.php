<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('check:user-points')->dailyAt('00:00');
Schedule::command('check:account-expiry')->dailyAt('00:00');
// Add to your schedule method
Schedule::command('pdf:cleanup --days=2')
    ->daily()
    ->at('03:00');
// Generate sitemap weekly on Sunday at 2 AM
Schedule::command('app:generate-sitemap')
    ->weekly()
    ->sundays()
    ->at('02:00');
// Tidak perlu menjalankan command ini setiap hari
// Schedule::command('accounts:delete-inactive')->dailyAt('00:00');

// Jalankan queue worker tiap 2 menit
Schedule::command('queue:work --stop-when-empty --timeout=120')
    ->everyMinute()
    ->withoutOverlapping();
