<?php

namespace App\Http\Services;

use App\Http\Repositories\KurvaTableSettingRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class KurvaTableSettingService
{
    protected $settingRepository;

    public function __construct(KurvaTableSettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAll()
    {
        return $this->settingRepository->all();
    }

    public function getById($id)
    {
        try {
            return $this->settingRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::error("KurvaTableSettingService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function getByNamaTabel($namaTabel)
    {
        try {
            return $this->settingRepository->findByNamaTabel($namaTabel);
        } catch (ModelNotFoundException $e) {
            Log::error("KurvaTableSettingService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function create(array $data)
    {
        return $this->settingRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->settingRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->settingRepository->delete($id);
    }
}
