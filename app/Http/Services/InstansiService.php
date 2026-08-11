<?php

namespace App\Http\Services;

use App\Http\Repositories\InstansiRepository;
use App\Models\Instansi;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class InstansiService
{
    protected $instansiRepository;

    public function __construct(InstansiRepository $instansiRepository)
    {
        $this->instansiRepository = $instansiRepository;
    }

    public function getAll($page)
    {
        return $this->instansiRepository->all($page);
    }

    public function createInstansi(array $data)
    {
        return $this->instansiRepository->create($data);
    }

    public function updateInstansi(string $id, array $data)
    {
        try {
            return $this->instansiRepository->update($id, $data);
        } catch (ModelNotFoundException $e) {
            Log::error("Instansi with ID {$id} not found.");
            return null;
        }
    }

    public function deleteInstansi(string $id)
    {
        try {
            return $this->instansiRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Instansi with ID {$id} not found.");
            return false;
        }
    }

    public function getInstansiById(string $id)
    {
        return $this->instansiRepository->findById($id);
    }

    public function getInstansiByReferralCode(string $code)
    {
        return $this->instansiRepository->findByReferralCode($code);
    }

    public function addPoints(string $id, int $points)
    {
        return $this->instansiRepository->addPoints($id, $points);
    }
}
