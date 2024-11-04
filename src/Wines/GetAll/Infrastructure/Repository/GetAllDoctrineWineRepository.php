<?php

namespace App\Wines\GetAll\Infrastructure\Repository;

use App\Wines\GetAll\Domain\Entity\Wine;
use App\Wines\GetAll\Domain\Interfaces\GetAllWineRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GetAllDoctrineWineRepository extends ServiceEntityRepository implements GetAllWineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wine::class);
    }

    public function getAllWines()
    {

        return $this->findAll();
    }
}