<?php

namespace App\Sensors\GetAll\Aplication;

use App\Sensors\GetAll\Domain\Interfaces\GetAllSensorRepositoryInterface;

class GetAllSensorService
{
    private GetAllSensorRepositoryInterface $getAllSensorRepository;

    public function __construct(GetAllSensorRepositoryInterface $getAllSensorRepository)
    {
        $this->getAllSensorRepository = $getAllSensorRepository;
    }

    public function getAll(){
        return $this->getAllSensorRepository->getAllOrderByName();
    }
}