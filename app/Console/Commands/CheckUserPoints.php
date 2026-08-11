<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\LowPointsNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckUserPoints extends Command
{
    protected $signature = 'check:user-points';
    protected $description = 'Check users with points below 100 and notify them';

    public function handle()
    {
        $now = now();
        $count = 0;

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            if ($user->hasRole('super-admin')) {
                continue;
            }

            // Calculate the user's points
            $points = DB::table('point_batches')
                ->where(function ($q) use ($user) {
                    if ($user->instansi_id) {
                        $q->where('instansi_id', $user->instansi_id);
                    } else {
                        $q->where('user_id', $user->id);
                    }
                })
                ->where('remaining_points', '>', 0)
                ->where('expired_at', '>=', $now)
                ->sum('remaining_points');
            // If points are less than 100, notify the user
            if ($points > 0 && $points <= 100) {
                $user->notify(new LowPointsNotification($points));
                $count++;
            }
        }

        $this->info("Sent low points notification to {$count} users");
        Log::info("Sent low points notification to {$count} users");
        return Command::SUCCESS;
    }
}
