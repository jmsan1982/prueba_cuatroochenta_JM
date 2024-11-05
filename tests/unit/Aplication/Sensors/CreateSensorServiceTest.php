<?php

namespace App\Tests\Unit\Aplication\Sensors;

use App\Sensors\Create\Aplication\CreateSensorService;
use App\Sensors\Create\Domain\Entity\Sensor;
use App\Sensors\Create\Domain\Repository\CreateSensorRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateSensorServiceTest extends TestCase
{
    private $createSensorRepositoryMock;
    private $createSensorService;

    protected function setUp(): void
    {
        $this->createSensorRepositoryMock = $this->createMock(CreateSensorRepositoryInterface::class);
        $this->createSensorService = new CreateSensorService($this->createSensorRepositoryMock);
    }

    public function testCreateSensorSuccessfully()
    {
        $name = 'Temperature Sensor';

        $this->createSensorRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Sensor::class));

        $sensor = $this->createSensorService->execute($name);

        $this->assertInstanceOf(Sensor::class, $sensor);
        $this->assertEquals($name, $sensor->getName());
    }

    public function testCreateSensorWithEmptyName()
    {
        $name = '';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Sensor name cannot be empty');

        $this->createSensorService->execute($name);
    }
}
