<?php

namespace App\Http\Repositories;

use App\Models\Testimoni;
use Illuminate\Support\Facades\DB;

class TestimoniRepository
{
    public function allQuery()
    {
        return DB::table('testimonis')
            ->join('users', 'testimonis.user_id', '=', 'users.id')
            ->select('testimonis.*', 'users.name as user_name');
    }

    public function paginated($page = 25, $search = null)
    {
        return DB::table('testimonis')
            ->join('users', 'testimonis.user_id', '=', 'users.id')
            ->select('testimonis.*', 'users.name as user_name')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('testimonis.testimoni', 'LIKE', "%{$search}%");
                });
            })
            ->paginate($page);
    }

    public function find($id)
    {
        // Implementasi mencari data berdasarkan ID
        return Testimoni::find($id);
    }

    public function create(array $data)
    {
        // Implementasi membuat data baru
        return Testimoni::create($data);
    }

    public function update($id, array $data): bool
    {
        // Implementasi mengupdate data
        $testimoni = $this->find($id);
        return $testimoni ? $testimoni->update($data) : false;
    }

    public function delete($id): bool
    {
        // Implementasi menghapus data
        $testimoni = $this->find($id);
        return $testimoni ? $testimoni->delete() : false;
    }

    public function findByUserId($userId)
    {
        return Testimoni::where('user_id', $userId)->first();
    }

    public function exists($userId): bool
    {
        return Testimoni::where('user_id', $userId)->exists();
    }
}
