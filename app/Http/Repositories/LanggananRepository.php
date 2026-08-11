<?php

namespace App\Http\Repositories;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;

class LanggananRepository
{
    public function all($page = 25, $search = null)
    {
        return DB::table("user_subscriptions")
            ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('users', 'user_subscriptions.user_id', '=', 'users.id')
            ->leftJoin('instansis', 'user_subscriptions.instansi_id', '=', 'instansis.id')
            ->select(
                'user_subscriptions.*',
                'subscriptions.name as subscription_name',
                'users.name as user_name',
                'instansis.name as instansi_name'
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('user_subscriptions.order_id', 'LIKE', "%{$search}%")  // Cari berdasarkan Order ID
                        ->orWhere('subscriptions.name', 'LIKE', "%{$search}%")  // Nama Paket (Subscription Name)
                        ->orWhere('instansis.name', 'LIKE', "%{$search}%")  // Nama Instansi
                        ->orWhere('users.name', 'LIKE', "%{$search}%");  // Nama User
                });
            })
            ->orderBy('user_subscriptions.created_at', 'desc')
            ->paginate($page);
    }

    public function allByUserId($page = 25, $search = null, $userId)
    {
        return DB::table("user_subscriptions")
            ->where('user_subscriptions.user_id', $userId)
            ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
            ->select('user_subscriptions.*', 'subscriptions.name as subscription_name')
            ->orderBy('user_subscriptions.created_at', 'desc')
            ->paginate($page);
    }

    public function allByInstansiId($page = 25, $search = null, $instansiId)
    {
        return DB::table("user_subscriptions")
            ->where('instansi_id', $instansiId)
            ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
            ->select('user_subscriptions.*', 'subscriptions.name as subscription_name')
            ->orderBy('user_subscriptions.created_at', 'desc')
            ->paginate($page);
    }

    public function find($id)
    {
        // Implementasi mencari data berdasarkan ID
        return UserSubscription::find($id);
    }

    public function create(array $data)
    {
        // Implementasi membuat data baru
        return UserSubscription::create($data);
    }

    public function update($id, array $data)
    {
        // Implementasi mengupdate data
        $subscription = $this->find($id);
        return $subscription ? $subscription->update($data) : false;
    }

    public function delete($id)
    {
        // Implementasi menghapus data
        $subscription = $this->find($id);
        return $subscription ? $subscription->delete() : false;
    }
}
