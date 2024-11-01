<?php

namespace App\Sensors\Create\Domain\Repository;


use App\Sensors\Create\Domain\Entity\Sensor;

interface CreateSensorRepositoryInterface
{
    public function save(Sensor $sensor):void;
}