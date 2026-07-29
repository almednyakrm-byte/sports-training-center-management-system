<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestFacilities extends TestCase
{
    private $facilitiesController;
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->facilitiesController = new FacilitiesController();
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetFacilities()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Facility 1'],
                ['id' => 2, 'name' => 'Facility 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM facilities')
            ->willReturn($stmt);

        $result = $this->facilitiesController->getFacilities($this->request, $this->response, $this->pdo);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetFacilityById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Facility 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM facilities WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->facilitiesController->getFacilityById($this->request, $this->response, $this->pdo, 1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateFacility()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Facility 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO facilities (name) VALUES (?)')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Facility 1']);

        $result = $this->facilitiesController->createFacility($this->request, $this->response, $this->pdo);
        $this->assertIsArray($result);
        $this->assertEquals(201, $result['status']);
    }

    public function testUpdateFacility()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Facility 1', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE facilities SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Facility 1']);

        $result = $this->facilitiesController->updateFacility($this->request, $this->response, $this->pdo, 1);
        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
    }

    public function testDeleteFacility()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM facilities WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->facilitiesController->deleteFacility($this->request, $this->response, $this->pdo, 1);
        $this->assertIsArray($result);
        $this->assertEquals(204, $result['status']);
    }
}