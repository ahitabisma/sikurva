<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AccountExpiryNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckAccountExpired extends Command
{
    protected $signature = 'check:account-expiry';
    protected $description = 'Check users whose accounts will expire within a week and notify them';

    public function handle()
    {
        $now = now();
        $oneWeekLater = $now->copy()->addDays(7);
        $count = 0;

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            if ($user->hasRole('super-admin')) {
                continue;
            }

            // Find soon-to-expire point batches for this user
            $expiringBatches = DB::table('point_batches')
                ->where(function ($q) use ($user) {
                    if ($user->instansi_id) {
                        $q->where('instansi_id', $user->instansi_id);
                    } else {
                        $q->where('user_id', $user->id);
                    }
                })
                ->where('expired_at', '>', $now)
                ->where('expired_at', '<=', $oneWeekLater)
                ->orderBy('expired_at')
                ->first();

            Command::info("Checking user account for: {$oneWeekLater}");
            // If there are expiring batches, notify the user
            if ($expiringBatches) {
                $expiryDate = Carbon::parse($expiringBatches->expired_at);
                // Use startOfDay on both dates to get whole days only
                $daysRemaining = $now->startOfDay()->diffInDays($expiryDate->startOfDay());

                $user->notify(new AccountExpiryNotification($daysRemaining, $expiryDate));
                $count++;
            }
        }

        $this->info("Sent account expiry notification to {$count} users");
        Log::info("Sent account expiry notification to {$count} users");
        return Command::SUCCESS;
    }
}
