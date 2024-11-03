<?php

namespace App\Measurement\Create\Aplication;

use App\Measurement\Create\Domain\Entity\Measurement;
use App\Measurement\Create\Domain\Repository\CreateMeasurementRepositoryInterface;

class CreateMeasurementService
{
    private CreateMeasurementRepositoryInterface $measurementRepository;

    public function __construct(CreateMeasurementRepositoryInterface $measurementRepository)
    {
        $this->measurementRepository = $measurementRepository;
    }

    public function execute(int $id_wine, int $id_sensor, string $color, string $temperature, string $alcohol_content, string $ph)
    {
        $measurement = new Measurement($id_wine, $id_sensor, $color, $temperature, $alcohol_content, $ph);
        $this->measurementRepository->save($measurement);

        return $measurement;
    }
}