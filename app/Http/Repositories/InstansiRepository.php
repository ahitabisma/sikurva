<?php

namespace App\Http\Repositories;

use App\Models\Instansi;
use Illuminate\Support\Facades\DB;

class InstansiRepository
{
    public function all($page = 25)
    {
        return DB::table('instansis')->paginate($page);
    }

    public function findByReferralCode(string $code)
    {
        return Instansi::where('referral_code', $code)->first();
    }

    public function create(array $data): Instansi
    {
        return Instansi::create($data);
    }

    public function findById(string $id): ?Instansi
    {
        return Instansi::find($id);
    }

    public function update(string $id, array $data): bool
    {
        $instansi = $this->findById($id);
        return $instansi ? $instansi->update($data) : false;
    }

    public function delete(string $id): bool
    {
        $instansi = $this->findById($id);
        return $instansi ? $instansi->delete() : false;
    }

    public function addPoints(string $id, int $points)
    {
        $instansi = $this->findById($id);

        if (!$instansi) {
            return false;
        }

        $instansi->points += $points;
        $instansi->save();
        return true;
    }
}
