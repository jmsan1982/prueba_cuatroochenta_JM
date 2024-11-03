<?php

namespace App\Measurement\Create\Domain\Repository;

use App\Measurement\Create\Domain\Entity\Measurement;

interface CreateMeasurementRepositoryInterface
{
    public function save(Measurement $measurement):void;
}