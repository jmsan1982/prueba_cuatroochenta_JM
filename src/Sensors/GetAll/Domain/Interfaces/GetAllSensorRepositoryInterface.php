<?php

namespace App\Sensors\GetAll\Domain\Interfaces;

use App\Sensors\Create\Domain\Entity\Sensor;

interface GetAllSensorRepositoryInterface
{
    /**
     * @return Sensor[]
     */
    public function getAllOrderByName():array;
}