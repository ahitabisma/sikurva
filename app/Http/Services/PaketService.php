<?php

namespace App\Http\Services;

use App\Http\Repositories\PaketRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class PaketService
{
    private $paketRepository;

    public function __construct(PaketRepository $paketRepository)
    {
        $this->paketRepository = $paketRepository;
    }

    public function getAll($page, $search)
    {
        return $this->paketRepository->all($page, $search);
    }

    public function getById($id)
    {
        try {
            return $this->paketRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::error("PaketService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function create(array $data)
    {
        return $this->paketRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->paketRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->paketRepository->delete($id);
    }
}
