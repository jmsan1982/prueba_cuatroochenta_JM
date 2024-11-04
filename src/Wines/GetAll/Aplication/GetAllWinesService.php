<?php

namespace App\Wines\GetAll\Aplication;

use App\Wines\GetAll\Domain\Interfaces\GetAllWineRepositoryInterface;

class GetAllWinesService
{
    private GetAllWineRepositoryInterface $getAllWinesRepository;

    public function __construct(GetAllWineRepositoryInterface $getAllSensorService){
        $this->getAllWinesRepository = $getAllSensorService;
    }

    function getAllWines()
    {
        return $this->getAllWinesRepository->getAllWines();
    }
}