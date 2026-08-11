<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function update(string $id, array $data): bool
    {
        $user = $this->findById($id);
        return $user ? $user->update($data) : false;
    }

    public function delete(string $id): bool
    {
        $user = $this->findById($id);
        return $user ? $user->delete() : false;
    }

    public function findByReferralCode(string $code): ?User
    {
        return User::where('referral_code', $code)->first();
    }
}
