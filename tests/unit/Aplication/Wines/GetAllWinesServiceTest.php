<?php

namespace App\Tests\Unit\Aplication\Wines;

use App\Wines\GetAll\Aplication\GetAllWinesService;
use App\Wines\GetAll\Domain\Entity\Wine;
use App\Wines\GetAll\Domain\Interfaces\GetAllWineRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetAllWinesServiceTest extends TestCase
{
    private $getAllWinesRepositoryMock;
    private $getAllWinesService;

    protected function setUp(): void
    {
        $this->getAllWinesRepositoryMock = $this->createMock(GetAllWineRepositoryInterface::class);
        $this->getAllWinesService = new GetAllWinesService($this->getAllWinesRepositoryMock);
    }

    public function testGetAllWinesSuccessfully()
    {
        $wine1 = new Wine('Wine 1', '2020');
        $wine2 = new Wine('Wine 2', '2021');

        $this->getAllWinesRepositoryMock
            ->method('getAllWines')
            ->willReturn([$wine1, $wine2]);

        $wines = $this->getAllWinesService->getAllWines();

        $this->assertIsArray($wines);
        $this->assertCount(2, $wines);

        $this->assertInstanceOf(Wine::class, $wines[0]);
        $this->assertEquals('Wine 1', $wines[0]->getName());
        $this->assertEquals('2020', $wines[0]->getYear());

        $this->assertEquals('Wine 2', $wines[1]->getName());
        $this->assertEquals('2021', $wines[1]->getYear());
    }

    public function testGetAllWinesEmptyList()
    {
        $this->getAllWinesRepositoryMock
            ->method('getAllWines')
            ->willReturn([]);

        $wines = $this->getAllWinesService->getAllWines();

        $this->assertIsArray($wines);
        $this->assertCount(0, $wines);
    }
}

