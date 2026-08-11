<?php

namespace App\Http\Repositories;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class PaketRepository
{
    public function all($page = 25, $search = null)
    {
        // Implementasi mengambil semua data & Search
        return DB::table('subscriptions')
            ->select('subscriptions.*')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('subscriptions.name', 'LIKE', "%{$search}%");
                });
            })
            ->paginate($page);
    }

    public function find($id)
    {
        // Implementasi mencari data berdasarkan ID
        return Subscription::find($id);
    }

    public function create(array $data): Subscription
    {
        // Implementasi membuat data baru
        return Subscription::create($data);
    }

    public function update($id, array $data): bool
    {
        // Implementasi mengupdate data
        $paket = $this->find($id);
        return $paket ? $paket->update($data) : false;
    }

    public function delete($id): bool
    {
        // Implementasi menghapus data
        $paket = $this->find($id);
        return $paket ? $paket->delete() : false;
    }
}
