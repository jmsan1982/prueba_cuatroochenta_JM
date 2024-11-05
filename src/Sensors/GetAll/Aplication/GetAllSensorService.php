<?php

namespace App\Sensors\GetAll\Aplication;

use App\Sensors\Create\Domain\Entity\Sensor;
use App\Sensors\GetAll\Domain\Interfaces\GetAllSensorRepositoryInterface;

class GetAllSensorService
{
    private GetAllSensorRepositoryInterface $getAllSensorRepository;

    public function __construct(GetAllSensorRepositoryInterface $getAllSensorRepository)
    {
        $this->getAllSensorRepository = $getAllSensorRepository;
    }

    /**
     * @return Sensor[]
     */
    public function getAll():array
    {
        return $this->getAllSensorRepository->getAllOrderByName();
    }
}