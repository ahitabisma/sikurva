<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AccountDeletionWarningNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteInactiveAccount extends Command
{
    protected $signature = 'accounts:delete-inactive';
    protected $description = 'Delete accounts with no point purchases in the last 4 months';

    public function handle()
    {
        $now = now();
        $fourMonthsAgo = $now->copy()->subMonths(4);
        $oneWeekBeforeDeletion = $now->copy()->subMonths(4)->addDays(7);
        $notifiedCount = 0;
        $deletedCount = 0;

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Skip super-admin users
            if ($user->hasRole('super-admin')) {
                continue;
            }

            // Find the latest point purchase for this user/institution
            $latestPurchase = DB::table('point_batches')
                ->where(function ($q) use ($user) {
                    if ($user->instansi_id) {
                        $q->where('instansi_id', $user->instansi_id);
                    } else {
                        $q->where('user_id', $user->id);
                    }
                })
                ->orderBy('expired_at', 'desc')
                ->first();

            // If no purchases ever made, use user creation date
            $lastActivityDate = Carbon::parse($latestPurchase->expired_at);

            Command::info("Checking user account for: {$lastActivityDate}");
            Command::info("Dif: {$lastActivityDate->diff($fourMonthsAgo)}");

            // If last activity is older than 4 months, delete the account
            if ($lastActivityDate->lt($fourMonthsAgo)) {
                // Log the deletion
                Log::info("Deleting user account for: {$user->email} due to 4 months inactivity");

                // Remove all related data (this depends on your database structure)
                $this->deleteRelatedData($user);

                // Delete the user
                $user->delete();

                $deletedCount++;
            }
            // If approaching deletion (between 3 months 3 weeks and 4 months), send warning
            elseif ($lastActivityDate->lt($oneWeekBeforeDeletion) && $lastActivityDate->gt($fourMonthsAgo)) {
                // Calculate days remaining until deletion
                $daysRemaining = $now->startOfDay()->diffInDays(
                    $lastActivityDate->copy()->addMonths(4)->startOfDay(),
                    false
                );

                // Notify the user about imminent deletion
                $user->notify(new AccountDeletionWarningNotification($daysRemaining));
                $notifiedCount++;
            }
        }

        $this->info("Sent deletion warning notifications to {$notifiedCount} users");
        $this->info("Deleted {$deletedCount} inactive accounts");
        Log::info("Sent deletion warning notifications to {$notifiedCount} users");
        Log::info("Deleted {$deletedCount} inactive accounts");
        return Command::SUCCESS;
    }

    private function deleteRelatedData(User $user)
    {
        // Delete related data for this user or institution
        // Adjust these queries based on your actual database structure

        if ($user->instansi_id) {
            // Institution-specific cleanup
            DB::table('point_batches')->where('instansi_id', $user->instansi_id)->delete();
            // Add other institution-specific tables
        } else {
            // User-specific cleanup
            DB::table('point_batches')->where('user_id', $user->id)->delete();
            DB::table('notifications')->where('notifiable_id', $user->id)->delete();
            // Add other user-specific tables
        }

        // Clean up any shared data for both types
    }
}
