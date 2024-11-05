<?php

namespace App\Wines\GetAll\Infrastructure\Repository;

use App\Wines\GetAll\Domain\Entity\Wine;
use App\Wines\GetAll\Domain\Interfaces\GetAllWineRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GetAllDoctrineWineRepository extends ServiceEntityRepository implements GetAllWineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wine::class);
    }

    /**
     * @return Wine[]
     */
    public function getAllWines():array
    {

        return $this->findAll();
    }
}