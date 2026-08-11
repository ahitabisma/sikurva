<?php

namespace App\Http\Services;

use App\Http\Repositories\PointRepository;

class PointService
{
    protected $pointRepository;

    public function __construct(PointRepository $pointRepository)
    {
        $this->pointRepository = $pointRepository;
    }

    public function createBatch($type, $userId, $instansiId, $userSubscriptionId, $remainingPoints, $points, $expired)
    {
        return $this->pointRepository->createBatch($type, $userId, $instansiId, $userSubscriptionId, $remainingPoints, $points, $expired);
    }

    public function findSettingByName($name)
    {
        return $this->pointRepository->findSetting($name);
    }

    public function findSettingByNameAndUserType($name, $userType = null)
    {
        return $this->pointRepository->findSettingByNameAndUserType($name, $userType);
    }

    public function createTransaction($userId, $instansiId, $batchId, $pointSettingId, $patientId, $points, $type, $description, $referralCode = null)
    {
        return $this->pointRepository->createTransaction($userId, $instansiId, $batchId, $pointSettingId, $patientId, $points, $type, $description, $referralCode);
    }

    public function usage($userId, $instansiId, $points, $description, $pointSettingId = null, $patientId = null)
    {
        return $this->pointRepository->usage($userId, $instansiId, $points, $description, $pointSettingId, $patientId);
    }

    public function isPointEnough($userId, $instansiId, $neededPoints)
    {
        return $this->pointRepository->isPointEnough($userId, $instansiId, $neededPoints);
    }
}
