<?php

namespace App\Http\Services;

use App\Http\Repositories\TestimoniRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TestimoniService
{
    protected $testimoniRepository;

    public function __construct(TestimoniRepository $testimoniRepository)
    {
        $this->testimoniRepository = $testimoniRepository;
    }

    public function getAllPaginated($page = 25, $search = null)
    {
        return $this->testimoniRepository->paginated($page, $search);
    }

    public function getById($id)
    {
        try {
            return $this->testimoniRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::error("TestimoniService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function getByUserId($userId)
    {
        try {
            return $this->testimoniRepository->findByUserId($userId);
        } catch (ModelNotFoundException $e) {
            Log::error("TestimoniService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function exists($userId): bool
    {
        return $this->testimoniRepository->exists($userId);
    }

    public function create(array $data)
    {
        return $this->testimoniRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->testimoniRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->testimoniRepository->delete($id);
    }
}
