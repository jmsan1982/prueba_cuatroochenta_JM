<?php

namespace App\Sensors\Create\Aplication;

use App\Sensors\Create\Domain\Entity\Sensor;
use App\Sensors\Create\Domain\Repository\CreateSensorRepositoryInterface;

class CreateSensorService
{
    private CreateSensorRepositoryInterface $createSensorRepository;

    public function __construct(CreateSensorRepositoryInterface $createSensorRepository)
    {
        $this->createSensorRepository = $createSensorRepository;
    }

    public function execute(string $name):object
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Sensor name cannot be empty');
        }

        $sensor = new Sensor($name);
        $this->createSensorRepository->save($sensor);

        return $sensor;
    }
}