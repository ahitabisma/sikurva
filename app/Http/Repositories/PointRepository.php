<?php

namespace App\Http\Repositories;

use App\Models\Instansi;
use App\Models\PointBatch;
use App\Models\PointSetting;
use App\Models\PointTransaction;
use App\Models\User;
use App\Notifications\LowPointsNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PointRepository
{
    public function createBatch($type, $userId, $instansiId, $userSubscriptionId, $remainingPoints, $points, $expired)
    {
        Cache::forget('total_poin_user_' . $userId);
        Cache::forget('total_poin_instansi_' . $instansiId);

        return PointBatch::create([
            'user_id' => $userId ?? null,
            'instansi_id' => $instansiId ?? null,
            'user_subscription_id' => $userSubscriptionId ?? null,
            'type' => $type,
            'points' => $points,
            'remaining_points' => $remainingPoints,
            'expired_at' => $expired,
        ]);
    }

    public function createTransaction($userId, $instansiId, $batchId, $pointSettingId, $patientId, $points, $type, $description, $referralCode = null)
    {
        return PointTransaction::create([
            'user_id' => $userId ?? null,
            'instansi_id' => $instansiId ?? null,
            'point_batch_id' => $batchId ?? null,
            'point_setting_id' => $pointSettingId ?? null,
            'patient_id' => $patientId ?? null,
            'points' => $points,
            'type' => $type,
            'referral_code' => $referralCode,
            'description' => $description,
        ]);
    }

    public function findSetting($name)
    {
        return PointSetting::where('name', $name)->first();
    }

    public function findSettingByNameAndUserType($name, $userType = null)
    {
        return PointSetting::where('name', $name)
            ->where('user_type', $userType)
            ->first();
    }

    public function isPointEnough($userId, $instansiId, $neededPoints)
    {
        $now = now();

        $total = DB::table('point_batches')
            ->where(function ($q) use ($userId, $instansiId) {
                if ($instansiId) {
                    $q->where('instansi_id', $instansiId);
                } else {
                    $q->where('user_id', $userId);
                }
            })
            ->where('remaining_points', '>', 0)
            ->where('expired_at', '>=', $now)
            ->sum('remaining_points');

        return $total >= $neededPoints;
    }


    public function usage($userId, $instansiId, $neededPoints, $description, $pointSettingId = null, $patientId = null)
    {
        Cache::forget('total_poin_user_' . $userId);
        Cache::forget('total_poin_instansi_' . $instansiId);

        $now = now();

        return DB::transaction(function () use ($userId, $instansiId, $neededPoints, $description, $now, $pointSettingId, $patientId) {
            // 1. Ambil semua batch yang masih aktif & punya sisa poin
            $batches = DB::table('point_batches')
                ->where(function ($q) use ($userId, $instansiId) {
                    if ($instansiId) {
                        $q->where('instansi_id', $instansiId);
                    } else {
                        $q->where('user_id', $userId);
                    }
                })
                ->where('remaining_points', '>', 0)
                ->where('expired_at', '>=', $now)
                ->orderBy('expired_at')
                ->lockForUpdate() // Lock agar aman dalam transaksi
                ->get();

            $totalAvailable = $batches->sum('remaining_points');

            if ($totalAvailable < $neededPoints) {
                return false; // Tidak cukup poin
            }

            $usedPoints = 0;

            foreach ($batches as $batch) {
                $available = $batch->remaining_points;
                $toUse = min($neededPoints - $usedPoints, $available);

                // 2. Kurangi poin di batch
                DB::table('point_batches')
                    ->where('id', $batch->id)
                    ->decrement('remaining_points', $toUse);

                // 3. Catat transaksi
                DB::table('point_transactions')->insert([
                    'user_id'        => $userId,
                    'instansi_id'    => $instansiId,
                    'point_batch_id' => $batch->id,
                    'point_setting_id' => $pointSettingId,
                    'patient_id' => $patientId,
                    'points'         => $toUse,
                    'type'           => 'usage',
                    'description'    => $description,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);

                $usedPoints += $toUse;

                if ($usedPoints >= $neededPoints) {
                    break;
                }
            }

            // Cek remaining points setelah transaksi dan kirim notifikasi jika <= 100
            $remainingPoints = DB::table('point_batches')
                ->where(function ($q) use ($userId, $instansiId) {
                    if ($instansiId) {
                        $q->where('instansi_id', $instansiId);
                    } else {
                        $q->where('user_id', $userId);
                    }
                })
                ->where('expired_at', '>=', $now)
                ->sum('remaining_points');

            if ($remainingPoints <= 100) {
                if ($userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $user->notify(new LowPointsNotification($remainingPoints));
                    }
                } else if ($instansiId) {
                    $user = User::where('instansi_id', $instansiId)->first();
                    if ($user) {
                        $user->notify(new LowPointsNotification($remainingPoints));
                    }
                }
            }

            return true;
        });
    }
}
