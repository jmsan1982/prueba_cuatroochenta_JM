<?php

namespace App\Tests\Unit\Aplication\Sensors;

use App\Sensors\GetAll\Aplication\GetAllSensorService;
use App\Sensors\Create\Domain\Entity\Sensor;
use App\Sensors\GetAll\Domain\Interfaces\GetAllSensorRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetAllSensorServiceTest extends TestCase
{
    private $getAllSensorRepositoryMock;
    private $getAllSensorService;

    protected function setUp(): void
    {
        $this->getAllSensorRepositoryMock = $this->createMock(GetAllSensorRepositoryInterface::class);
        $this->getAllSensorService = new GetAllSensorService($this->getAllSensorRepositoryMock);
    }

    public function testGetAllSensorsSuccessfully()
    {
        $sensor1 = new Sensor('Temperature Sensor');
        $sensor2 = new Sensor('Humidity Sensor');

        $this->getAllSensorRepositoryMock
            ->method('getAllOrderByName')
            ->willReturn([$sensor1, $sensor2]);

        $sensors = $this->getAllSensorService->getAll();

        $this->assertIsArray($sensors);
        $this->assertCount(2, $sensors);
        $this->assertInstanceOf(Sensor::class, $sensors[0]);
        $this->assertEquals('Temperature Sensor', $sensors[0]->getName());
        $this->assertEquals('Humidity Sensor', $sensors[1]->getName());
    }

    public function testGetAllSensorsEmptyList()
    {
        $this->getAllSensorRepositoryMock
            ->method('getAllOrderByName')
            ->willReturn([]);

        $sensors = $this->getAllSensorService->getAll();

        $this->assertIsArray($sensors);
        $this->assertCount(0, $sensors);
    }
}

