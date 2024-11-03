<?php

namespace App\Measurement\Create\Infrastructure\Repository;

use App\Measurement\Create\Domain\Entity\Measurement;
use App\Measurement\Create\Domain\Repository\CreateMeasurementRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CreateDoctrineMeasurementRepository extends ServiceEntityRepository implements CreateMeasurementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function save(Measurement $measurement): void
    {
        $this->getEntityManager()->persist($measurement);
        $this->getEntityManager()->flush();
    }
}