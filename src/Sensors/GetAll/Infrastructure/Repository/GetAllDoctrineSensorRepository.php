<?php

namespace App\Sensors\GetAll\Infrastructure\Repository;



use App\Sensors\Create\Domain\Entity\Sensor;
use App\Sensors\GetAll\Domain\Entity\SensorGetAll;
use App\Sensors\GetAll\Domain\Interfaces\GetAllSensorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GetAllDoctrineSensorRepository extends ServiceEntityRepository implements GetAllSensorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensorGetAll::class);
    }

    /**
     * @return Sensor[]
     */
    public function getAllOrderByName():array
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query->select('s')
            ->from('App\Sensors\GetAll\Domain\Entity\SensorGetAll', 's')
            ->orderBy('s.name', 'ASC');

        return $query->getQuery()->getResult();
    }
}