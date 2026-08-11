<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\ApiSettingService;
use App\Http\Services\LanggananService;
use App\Http\Services\PaketService;
use App\Http\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LanggananController extends Controller
{
    protected $paketService;
    protected $langgananService;
    protected $midtransService;
    protected $apiSettingService;

    public function __construct(PaketService $paketService, LanggananService $langgananService, MidtransService $midtransService, ApiSettingService $apiSettingService)
    {
        $this->paketService = $paketService;
        $this->langgananService = $langgananService;
        $this->midtransService = $midtransService;
        $this->apiSettingService = $apiSettingService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $langganans = null;
        $instansiId = Auth::user()->instansi_id;
        $userId = Auth::user()->id;
        $page = 25;
        if ($instansiId != null) {
            $langganans = DB::table("user_subscriptions")
                ->where('user_subscriptions.instansi_id', $instansiId)
                ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
                ->leftJoin('point_batches', 'user_subscriptions.id', '=', 'point_batches.user_subscription_id')
                ->select('user_subscriptions.*', 'subscriptions.name as subscription_name', 'point_batches.remaining_points as sisa_point')
                ->orderBy('user_subscriptions.created_at', 'desc')
                ->paginate($page);
        } elseif (Auth::user()->instansi_id == null) {
            $langganans = DB::table("user_subscriptions")
                ->where('user_subscriptions.user_id', $userId)
                ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
                ->leftJoin('point_batches', 'user_subscriptions.id', '=', 'point_batches.user_subscription_id')
                ->select('user_subscriptions.*', 'subscriptions.name as subscription_name', 'point_batches.remaining_points as sisa_point')
                ->orderBy('user_subscriptions.created_at', 'desc')
                ->paginate($page);
        } else {
            abort(403);
        }

        return view('admin.langganan.index')->with([
            'title' => 'Langganan',
            'langganans' => $langganans,
            'clientKey' => $this->apiSettingService->getApiSetting('MIDTRANS_CLIENT_KEY'),
        ]);
    }

    public function create()
    {
        $pakets = DB::table('subscriptions')->where('status', 1)->orderBy('point', 'asc')->get();
        return view('admin.langganan.create')->with([
            'title' => 'Beli Langganan',
            'pakets' => $pakets,
            'clientKey' => $this->apiSettingService->getApiSetting('MIDTRANS_CLIENT_KEY'),
        ]);
    }

    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        // Get subscription package data
        $subscription = $this->paketService->getById($validatedData['subscription_id']);

        // Prepare data for saving
        $subscriptionData = [
            'user_id' => Auth::user()->instansi_id == null ? Auth::user()->id : null,
            'instansi_id' => Auth::user()->instansi_id != null ? Auth::user()->instansi_id : null,
            'subscription_id' => $validatedData['subscription_id'],
            'price' => $subscription->price,
            'point' => $subscription->point ?? 0,
            'duration' => $subscription->duration,
            'duration_type' => $subscription->duration_type,
            'status' => 'pending',
        ];

        // Save data using service
        $langganan = $this->langgananService->create($subscriptionData);

        // Generate Midtrans payment token
        $midtransResponse = $this->midtransService->createTransaction($langganan);

        // Return JSON response with the snap token for frontend
        return response()->json([
            'success' => true,
            'token' => $midtransResponse['token'],
            'subscription_id' => $midtransResponse['subscription_id']
        ]);
    }

    public function getSnapToken($id)
    {
        $langganan = $this->langgananService->getById($id);

        if (!$langganan || $langganan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Langganan tidak ditemukan atau sudah dibayar.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'token' => $langganan->snap_token
        ]);
    }

    public function cancel($id)
    {
        $isCancelled = $this->langgananService->update($id, ['status' => 'cancelled']);

        if (!$isCancelled) return back()->with('error', 'Terjadi kesalahan sehingga tidak dapat membatalkan langganan');

        return back()->with('success', 'Langganan berhasil dibatalkan!');
    }

    public function show($id)
    {
        $langganan = $this->langgananService->getById($id);

        if (!$langganan) {
            return redirect()->route('langganan.index')->with('error', 'Langganan tidak ditemukan.');
        }

        return view('admin.langganan.show')->with([
            'title' => 'Detail Langganan',
            'langganan' => $langganan,
        ]);
    }

    // Methods for handling payment completion
    public function paymentFinish(Request $request)
    {
        return redirect()->route('langganan.index')
            ->with('success', 'Pembayaran berhasil! Status langganan Anda akan diperbarui secara otomatis.');
    }

    public function paymentPending(Request $request)
    {
        return redirect()->route('langganan.index')
            ->with('error', 'Pembayaran belum selesai. Silahkan bayar langganan Anda.');
    }

    public function paymentUnfinish(Request $request)
    {
        return redirect()->route('langganan.index')
            ->with('error', 'Pembayaran belum selesai. Anda dapat melanjutkan pembayaran nanti.');
    }

    public function paymentError(Request $request)
    {
        return redirect()->route('langganan.index')
            ->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi nanti.');
    }
}
