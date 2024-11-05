<?php

namespace App\Measurement\Create\Aplication;

use App\Measurement\Create\Domain\Entity\Measurement;
use App\Measurement\Create\Domain\Repository\CreateMeasurementRepositoryInterface;
use App\Sensors\Create\Domain\Entity\Sensor;
use App\Wines\GetAll\Domain\Entity\Wine;
use Doctrine\ORM\EntityManagerInterface;

class CreateMeasurementService
{
    private CreateMeasurementRepositoryInterface $measurementRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CreateMeasurementRepositoryInterface $measurementRepository, EntityManagerInterface $entityManager)
    {
        $this->measurementRepository = $measurementRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @return Measurement
     */
    public function execute(int $id_wine, int $id_sensor, string $color, string $temperature, string $alcohol_content, string $ph):Measurement
    {
        $wine = $this->entityManager->getRepository(Wine::class)->find($id_wine);
        $sensor = $this->entityManager->getRepository(Sensor::class)->find($id_sensor);

        if (!$wine) {
            throw new \InvalidArgumentException("Wine not found for ID: $id_wine");
        }
        if (!$sensor) {
            throw new \InvalidArgumentException("Sensor not found for ID: $id_sensor");
        }
        $measurement = new Measurement($wine, $sensor, $color, $temperature, $alcohol_content, $ph);
        $this->measurementRepository->save($measurement);

        return $measurement;
    }
}