<?php

namespace App\Http\Services;

use App\Http\Repositories\LanggananRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class LanggananService
{
    private $langgananRepository;

    public function __construct(LanggananRepository $langgananRepository)
    {
        $this->langgananRepository = $langgananRepository;
    }

    public function getAll($page = 25, $search = null)
    {
        return $this->langgananRepository->all($page, $search);
    }

    public function getAllByUserId($page = 25, $search = null, $userId)
    {
        return $this->langgananRepository->allByUserId($page, $search, $userId);
    }

    public function getAllByInstansiId($page = 25, $search = null, $instansiId)
    {
        return $this->langgananRepository->allByInstansiId($page, $search, $instansiId);
    }

    public function getById($id)
    {
        try {
            return $this->langgananRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::error("LanggananService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function create(array $data)
    {
        return $this->langgananRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->langgananRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->langgananRepository->delete($id);
    }
}
