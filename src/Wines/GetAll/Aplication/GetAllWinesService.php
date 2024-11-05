<?php

namespace App\Wines\GetAll\Aplication;

use App\Wines\GetAll\Domain\Entity\Wine;
use App\Wines\GetAll\Domain\Interfaces\GetAllWineRepositoryInterface;

class GetAllWinesService
{
    private GetAllWineRepositoryInterface $getAllWinesRepository;

    public function __construct(GetAllWineRepositoryInterface $getAllSensorService){
        $this->getAllWinesRepository = $getAllSensorService;
    }

    /**
     * @return Wine[] Returns an array of Wine objects
     */
    function getAllWines():array
    {
        return $this->getAllWinesRepository->getAllWines();
    }
}