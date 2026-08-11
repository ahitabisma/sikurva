<?php

namespace App\Http\Controllers\SuperAdmin\Langganan;

use App\Http\Controllers\Controller;
use App\Http\Services\LanggananService;
use App\Http\Services\PaketService;
use App\Http\Services\PointService;
use App\Http\Services\UserService;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    protected $langgananService;
    protected $paketService;
    protected $pointService;
    protected $userService;

    public function __construct(LanggananService $langgananService, PaketService $paketService, PointService $pointService, UserService $userService)
    {
        $this->langgananService = $langgananService;
        $this->paketService = $paketService;
        $this->pointService = $pointService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $search = $request->input("search");

        $subscriptions = DB::table('subscriptions')
            ->leftJoin('user_subscriptions', function ($join) {
                $join->on('subscriptions.id', '=', 'user_subscriptions.subscription_id')
                    ->where('user_subscriptions.status', '=', 'paid');
            })
            ->select(
                'subscriptions.id',
                'subscriptions.name',
                DB::raw('COUNT(user_subscriptions.id) as total_users')
            )
            ->groupBy('subscriptions.id', 'subscriptions.name')
            ->get();

        $langganans = $this->langgananService->getAll(25, $search);
        return view("super-admin.langganan.transaksi.index")->with([
            'title' => 'Manajemen Langganan | Transaksi',
            'langganans' => $langganans,
            'subscriptions' => $subscriptions
        ]);
    }

    public function create()
    {
        $users = DB::table('users')->select('id', 'name')->where('is_nakes', false)->get();
        $instansis = DB::table('instansis')->select('id', 'name')->get();
        $pakets = DB::table('subscriptions')->where('status', 1)->orderBy('price', 'asc')->get();
        return view("super-admin.langganan.transaksi.create")->with([
            'title' => 'Tambah Transaksi',
            'users' => $users,
            'instansis' => $instansis,
            'pakets' => $pakets
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        // Find User
        $user = $this->userService->getUserById($validatedData['user_id']);
        $context = getInstansiOrUserContext($user);

        // Ambil data paket langganan dari database
        $subscription = $this->paketService->getById($validatedData['subscription_id']);

        // Siapkan data untuk disimpan
        $subscriptionData = [
            'user_id' => $context['user_id'],
            'instansi_id' => $context['instansi_id'],
            'subscription_id' => $validatedData['subscription_id'],
            'price' => $subscription->price,
            'point' => $subscription->point ?? 0,
            'duration' => $subscription->duration,
            'duration_type' => $subscription->duration_type,
            'status' => $validatedData['status'], // Use the selected status
            'started_at' => $validatedData['status'] === 'paid' ? now() : null,
            'expired_at' => $validatedData['status'] === 'paid' ?
                calculateExpiredAt($subscription->duration_type, $subscription->duration) : null,
        ];

        // Simpan data menggunakan service
        $langganan = $this->langgananService->create($subscriptionData);

        // Update order_id setelah record dibuat
        $langganan->update([
            'order_id' => 'EKRV-' . $langganan->id . '-' .  time(),
        ]);

        // Handle point batch and transaction creation for paid status
        if ($validatedData['status'] === 'paid') {
            $expired = $langganan->expired_at;
            $userId = $langganan->user_id;
            $instansiId = $langganan->instansi_id;

            // Create point batch
            $batch = $this->pointService->createBatch(
                'purchase',
                $userId,
                $instansiId,
                $langganan->id,
                $langganan->point,
                $langganan->point,
                $expired
            );

            // Create point transaction
            $this->pointService->createTransaction(
                $userId,
                $instansiId,
                $batch->id,
                null,
                null,
                $langganan->point,
                'purchase',
                "Langganan Paket {$subscription->name}",
                null
            );
        }

        $cacheUserHeaderKey = 'header_user_' . $user->id;
        Cache::forget($cacheUserHeaderKey);

        // Redirect dengan pesan sukses
        return redirect()->route('super-admin.langganan.transaksi.index')
            ->with('success', 'Transaksi langganan berhasil dibuat!');
    }

    public function show($id)
    {
        $langganan = $this->langgananService->getById($id);

        if (!$langganan) {
            return redirect()->route('super-admin.langganan.transaksi.index')
                ->with('error', 'Langganan tidak ditemukan.');
        }

        $pointTransactions = DB::table('point_transactions')
            ->join('point_batches', 'point_transactions.point_batch_id', '=', 'point_batches.id')
            ->join('user_subscriptions', 'point_batches.user_subscription_id', '=', 'user_subscriptions.id')
            ->leftJoin('patients', 'point_transactions.patient_id', '=', 'patients.id')
            ->where('point_transactions.type', 'usage')
            ->where('point_batches.user_subscription_id', $langganan->id)
            ->select('point_transactions.created_at', 'point_transactions.points', 'point_batches.type', 'point_transactions.description', 'patients.nama as patient_name')
            ->orderBy('point_transactions.created_at', 'desc')
            ->paginate(25);

        return view('super-admin.langganan.transaksi.show')->with([
            'title' => 'Detail Transaksi',
            'langganan' => $langganan,
            'pointTransactions' => $pointTransactions,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:paid,cancelled',
        ]);

        $langganan = $this->langgananService->getById($id);

        if (!$langganan) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status transaksi');
        }

        if ($langganan->status === 'paid') {
            return redirect()->back()->with('error', 'Transaksi dengan status Paid tidak dapat diubah.');
        }

        if ($request->status === 'paid') {
            // Ambil data paket langganan
            $subscription = $this->paketService->getById($langganan->subscription_id);

            // Expired
            $expired = calculateExpiredAt($subscription->duration_type, $subscription->duration);

            // Update User Subscription
            $langganan->update([
                'status' => $request->status,
                'started_at' => now(),
                'expired_at' => $expired,
            ]);

            if ($langganan->instansi_id) {
                // Insert Point Batch
                $batch = $this->pointService->createBatch('purchase', $langganan->user_id ?? null, $langganan->instansi_id ?? null, $langganan->id, $langganan->point, $langganan->point, $expired);

                // Insert Point Transaction
                $this->pointService->createTransaction(
                    $langganan->user_id ?? null,
                    $langganan->instansi_id ?? null,
                    $batch->id,
                    null,
                    null,
                    $langganan->point,
                    'purchase',
                    "Langganan Paket {$subscription->name}",
                    null
                );
            } else {

                // Insert Point Batch
                $batch = $this->pointService->createBatch('purchase', $langganan->user_id ?? null, $langganan->instansi_id ?? null, $langganan->id, $langganan->point, $langganan->point, $expired);

                // Insert Point Transaction
                $this->pointService->createTransaction(
                    $langganan->user_id ?? null,
                    $langganan->instansi_id ?? null,
                    $batch->id,
                    null,
                    null,
                    $langganan->point,
                    'purchase',
                    "Langganan Paket {$subscription->name}",
                    null
                );
            }
        } else {
            $langganan->update(['status' => $request->status]);
        }

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $langganan = $this->langgananService->getById($id);

        if (!$langganan) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus transaksi');
        }

        // Prevent deletion if status is 'paid'
        if ($langganan->status === 'paid') {
            return redirect()->back()->with('error', "Transaksi dengan status Paid tidak dapat dihapus.");
        }

        // Delete the associated photo if it exists
        if ($langganan->photo) {
            Storage::disk('public')->delete($langganan->photo);
        }

        // Attempt to delete the subscription
        $isDeleted = $this->langgananService->delete($id);

        if (!$isDeleted) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus transaksi. Silakan coba lagi.');
        }

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }

    // Search user by email
    public function searchByEmail(Request $request)
    {
        $email = $request->input('email');

        if (empty($email)) {
            return response()->json([]);
        }

        // Get super admin IDs to exclude them
        $superAdminIds = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'super-admin')
            ->where('model_has_roles.model_type', 'App\Models\User')
            ->pluck('model_has_roles.model_id');

        // Subquery: point_batches (user)
        $pointBatchUser = DB::table('point_batches')
            ->selectRaw('user_id, SUM(remaining_points) as total_points, MAX(expired_at) as latest_expired')
            ->where('expired_at', '>=', now())
            ->groupBy('user_id');

        // Subquery: point_batches (instansi)
        $pointBatchInstansi = DB::table('point_batches')
            ->selectRaw('instansi_id, SUM(remaining_points) as total_points, MAX(expired_at) as latest_expired')
            ->where('expired_at', '>=', now())
            ->groupBy('instansi_id');

        // Main query
        $users = DB::table('users')
            ->whereNotIn('users.id', $superAdminIds)
            ->where(function ($query) use ($email) {
                $query->where('users.email', 'like', "%{$email}%")
                    ->orWhere('users.name', 'like', "%{$email}%");
            })
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->leftJoinSub($pointBatchUser, 'pb_user', 'users.id', '=', 'pb_user.user_id')
            ->leftJoinSub($pointBatchInstansi, 'pb_instansi', 'users.instansi_id', '=', 'pb_instansi.instansi_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.is_nakes',
                'instansis.name as instansi_name',
                DB::raw('COALESCE(pb_user.total_points, 0) as total_points'),
                'pb_user.latest_expired',
                DB::raw('COALESCE(pb_instansi.total_points, 0) as total_instansi_points'),
                'pb_instansi.latest_expired as instansi_latest_expired'
            )
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}
