<?php

namespace App\Sensors\Create\Infrastructure\Repository;

use App\Sensors\Create\Domain\Entity\Sensor;
use App\Sensors\Create\Domain\Repository\CreateSensorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CreateDoctrineSensorRepository extends ServiceEntityRepository implements CreateSensorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sensor::class);
    }

    public function save(Sensor $sensor): void
    {
        $this->getEntityManager()->persist($sensor);
        $this->getEntityManager()->flush();
    }
}
