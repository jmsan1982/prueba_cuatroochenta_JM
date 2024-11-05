<?php

namespace App\Wines\GetAll\Domain\Interfaces;

use App\Wines\GetAll\Domain\Entity\Wine;

Interface GetAllWineRepositoryInterface
{
    /**
     * @return Wine[] Returns an array of Wine objects
     */
    public function getAllWines():array;
}
