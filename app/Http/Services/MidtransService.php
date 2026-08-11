<?php

namespace App\Http\Services;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\Config as LaravelConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    protected $apiSettingService;

    public function __construct(ApiSettingService $apiSettingService)
    {
        $this->apiSettingService = $apiSettingService;

        // Get Midtrans settings from database
        $merchantId = $this->apiSettingService->getApiSetting('MIDTRANS_MERCHANT_ID');
        $clientKey = $this->apiSettingService->getApiSetting('MIDTRANS_CLIENT_KEY');
        $serverKey = $this->apiSettingService->getApiSetting('MIDTRANS_SERVER_KEY');

        // Set your Midtrans configuration with fallback to config values
        Config::$serverKey = $serverKey ?: null;
        Config::$clientKey = $clientKey ?: null;

        // Other settings from config
        Config::$is3ds = LaravelConfig::get('midtrans.is_3ds', true);
        Config::$isSanitized = LaravelConfig::get('midtrans.is_sanitized', true);
        Config::$isProduction = true;

        // Enable notification URL override
        Config::$overrideNotifUrl = route('midtrans.webhook');
    }

    public function createTransaction(UserSubscription $userSubscription)
    {
        try {
            $orderId = 'EKRV-' . $userSubscription->id . '-' . time();

            // Update the order ID in the database
            $userSubscription->update(['order_id' => $orderId]);

            // Prepare customer details
            if ($userSubscription->instansi_id) {
                $user = DB::table('users')
                    ->where('instansi_id', $userSubscription->instansi_id)
                    ->join('instansis', 'users.instansi_id', '=', 'instansis.id')
                    ->select('users.name', 'users.email', 'users.phone', 'instansis.name as instansi_name')
                    ->first();
            } else {
                $user = DB::table('users')->where('id', $userSubscription->user_id)->first();
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$userSubscription->price,
                ],
                'customer_details' => [
                    'first_name' => ($userSubscription->instansi_id ? $user->instansi_name : $user->name) ?? 'Customer',
                    'email' => $user->email ?? '',
                    'phone' => $user->phone ?? '',
                ],
                'item_details' => [
                    [
                        'id' => 'SUB-' . $userSubscription->subscription_id,
                        'price' => (int)$userSubscription->price,
                        'quantity' => 1,
                        'name' => $userSubscription->subscription->name . ' (' . $userSubscription->duration . ' ' . $userSubscription->duration_type . ')',
                    ],
                ],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'unfinish' => route('payment.unfinish'),
                    'error' => route('payment.error'),
                ],
            ];

            // Get Snap Token only (not URL)
            $snapToken = Snap::getSnapToken($params);

            // Save the token to the subscription
            $userSubscription->update([
                'snap_token' => $snapToken,
            ]);

            return [
                'token' => $snapToken,
                'subscription_id' => $userSubscription->id,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans transaction creation failed: ' . $e->getMessage(), [
                'user_subscription_id' => $userSubscription->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
