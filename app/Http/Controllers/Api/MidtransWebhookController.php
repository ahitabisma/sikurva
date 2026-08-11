<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\ApiSettingService;
use App\Http\Services\LanggananService;
use App\Http\Services\PaketService;
use App\Http\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class MidtransWebhookController extends Controller
{
    protected $langgananService;
    protected $pointService;
    protected $paketService;
    protected $apiSettingService;

    public function __construct(
        LanggananService $langgananService,
        PointService $pointService,
        PaketService $paketService,
        ApiSettingService $apiSettingService
    ) {
        $this->langgananService = $langgananService;
        $this->pointService = $pointService;
        $this->paketService = $paketService;
        $this->apiSettingService = $apiSettingService;
    }

    public function handle(Request $request)
    {
        $serverKey = $this->apiSettingService->getApiSetting('MIDTRANS_SERVER_KEY');
        Config::$serverKey = $serverKey ?: null;
        Config::$isProduction = config('midtrans.is_production');

        $signatureKey = hash(
            "sha512",
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                Config::$serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            Log::info('Signature key mismatch for order ID: ' . $request->order_id);

            return response()->json(['status' => 'success']);
        }

        $langganan = DB::table('user_subscriptions')
            ->where('order_id', $request->order_id)
            ->first();

        if (!$langganan) {
            Log::info('Payment not found but return true');

            return response()->json(['status' => 'success']);
        }

        $langganan = $this->langgananService->getById($langganan->id);
        $subscription = $this->paketService->getById($langganan->subscription_id);

        if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
            $langganan->status = 'paid'; // Status pembayaran berhasil
            $this->processSuccessfulPayment($langganan, $subscription);
        } elseif ($request->transaction_status == 'cancel') {
            $langganan->status = 'cancelled'; // Status pembayaran gagal atau kadaluarsa
        } elseif ($request->transaction_status == 'expire') {
            $langganan->status = 'expired'; // Status pembayaran gagal atau kadaluarsa
        } elseif ($request->transaction_status == 'pending') {
            $langganan->status = 'pending'; // Status menunggu pembayaran
        }

        $langganan->save();

        Log::info('Payment status updated for order ID: ' . $request->order_id, [
            'status' => $langganan->status
        ]);

        return response()->json(['status' => 'success']);
    }

    private function updateSubscriptionStatus($subscriptionId, $status)
    {
        return $this->langgananService->update($subscriptionId, ['status' => $status]);
    }

    private function processSuccessfulPayment($langganan, $subscription)
    {
        try {
            DB::beginTransaction();

            // Update subscription status and set start/expiry dates
            $startDate = now();
            $expiredAt = $langganan->duration_type == 'bulan'
                ? $startDate->copy()->addMonths($langganan->duration)
                : $startDate->copy()->addYears($langganan->duration);

            $updateData = [
                'status' => 'paid',
                'started_at' => $startDate,
                'expired_at' => $expiredAt
            ];

            $this->updateSubscriptionStatus($langganan->id, 'paid');
            $this->langgananService->update($langganan->id, $updateData);

            // Create point batch and transaction
            $batch = $this->pointService->createBatch(
                'purchase',
                $langganan->user_id,
                $langganan->instansi_id,
                $langganan->id,
                $langganan->point,
                $langganan->point,
                $expiredAt
            );

            // Create point transaction
            $this->pointService->createTransaction(
                $langganan->user_id,
                $langganan->instansi_id,
                $batch->id,
                null,
                null,
                $langganan->point,
                'purchase',
                "Langganan Paket {$subscription->name}",
                null
            );

            DB::commit();
            $pointKey = $langganan->instansi_id ? 'total_poin_instansi_' . $langganan->instansi_id : 'total_poin_user_' . $langganan->id;
            Cache::forget($pointKey);

            Log::info('Payment processed successfully for order ID: ' . $langganan->order_id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing payment: ' . $e->getMessage());
            throw $e;
        }
    }
}
