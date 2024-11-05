<?php

namespace App\Tests\Unit\Aplication\Measurement;

use App\Measurement\Create\Aplication\CreateMeasurementService;
use App\Measurement\Create\Domain\Entity\Measurement;
use App\Measurement\Create\Domain\Repository\CreateMeasurementRepositoryInterface;
use App\Sensors\Create\Domain\Entity\Sensor;
use App\Wines\GetAll\Domain\Entity\Wine;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateMeasurementServiceTest extends TestCase
{
    private $measurementRepositoryMock;
    private $entityManagerMock;
    private $createMeasurementService;

    protected function setUp(): void
    {
        $this->measurementRepositoryMock = $this->createMock(CreateMeasurementRepositoryInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->createMeasurementService = new CreateMeasurementService($this->measurementRepositoryMock, $this->entityManagerMock);
    }

    public function testCreateMeasurementSuccessfully()
    {
        $id_wine = 1;
        $id_sensor = 1;
        $color = 'red';
        $temperature = '20';
        $alcohol_content = '13%';
        $ph = '3.5';

        $wineMock = $this->createMock(Wine::class);

        $sensorMock = $this->createMock(Sensor::class);

        $this->entityManagerMock
            ->method('getRepository')
            ->willReturnCallback(function ($entity) use ($wineMock, $sensorMock, $id_wine, $id_sensor) {
                if ($entity === Wine::class && $id_wine === 1) {
                    return $this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, [
                        'find' => $wineMock
                    ]);
                }
                if ($entity === Sensor::class && $id_sensor === 1) {
                    return $this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, [
                        'find' => $sensorMock
                    ]);
                }
                return $this->createMock(\Doctrine\ORM\EntityRepository::class);
            });

        $this->measurementRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Measurement::class));

        $measurement = $this->createMeasurementService->execute($id_wine, $id_sensor, $color, $temperature, $alcohol_content, $ph);

        $this->assertInstanceOf(Measurement::class, $measurement);
    }

    public function testCreateMeasurementWineNotFound()
    {
        $id_wine = 999;
        $id_sensor = 1;
        $color = 'blue';
        $temperature = '20';
        $alcohol_content = '13%';
        $ph = '5.5';

        $this->entityManagerMock
            ->method('getRepository')
            ->willReturnCallback(function ($entity) {
                return $this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, [
                    'find' => null
                ]);
            });

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Wine not found for ID: $id_wine");

        $this->createMeasurementService->execute($id_wine, $id_sensor, $color, $temperature, $alcohol_content, $ph);
    }

    public function testCreateMeasurementSensorNotFound()
    {
        $id_wine = 1;
        $id_sensor = 999;
        $color = 'orange';
        $temperature = '25';
        $alcohol_content = '16%';
        $ph = '5.5';

        $wineMock = $this->createMock(Wine::class);

        // Configure EntityManager to return wine entity, but no sensor entity
        $this->entityManagerMock
            ->method('getRepository')
            ->willReturnCallback(function ($entity) use ($wineMock) {
                if ($entity === Wine::class) {
                    return $this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, [
                        'find' => $wineMock
                    ]);
                }
                return $this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, [
                    'find' => null
                ]);
            });

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Sensor not found for ID: $id_sensor");

        $this->createMeasurementService->execute($id_wine, $id_sensor, $color, $temperature, $alcohol_content, $ph);
    }
}

