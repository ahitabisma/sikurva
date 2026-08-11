<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function getUserById(string $id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new ModelNotFoundException("User dengan ID {$id} tidak ditemukan.");
        }

        return $user;
    }

    public function findByEmail(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new ModelNotFoundException("User dengan Email {$email} tidak ditemukan.");
        }

        return $user;
    }

    public function updateUser(string $id, array $data)
    {
        $user = $this->getUserById($id);
        return $user->update($data);
    }

    public function deleteUser(string $id)
    {
        $user = $this->getUserById($id);
        return $user->delete();
    }

    public function getUserByReferralCode(string $code)
    {
        return $this->userRepository->findByReferralCode($code);
    }
}
