<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testالموظفين extends TestCase
{
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetAllالموظفين(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الموظفين')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $controller = new الموظفينController($this->pdo);
        $result = $controller->getAllالموظفين($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ], json_decode($result->getBody()->getContents(), true));
    }

    public function testGetالموظفينById(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM الموظفين WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $controller = new الموظفينController($this->pdo);
        $result = $controller->getالموظفينById($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'John Doe'], json_decode($result->getBody()->getContents(), true));
    }

    public function testCreateالموظفين(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO الموظفين (name) VALUES (:name)')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'John Doe');

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'John Doe']);

        $response = $this->createMock(ResponseInterface::class);

        $controller = new الموظفينController($this->pdo);
        $result = $controller->createالموظفين($request, $response);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals(['message' => 'الموظف created successfully'], json_decode($result->getBody()->getContents(), true));
    }

    public function testUpdateالموظفين(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE الموظفين SET name = :name WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Jane Doe');

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Jane Doe']);

        $response = $this->createMock(ResponseInterface::class);

        $controller = new الموظفينController($this->pdo);
        $result = $controller->updateالموظفين($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['message' => 'الموظف updated successfully'], json_decode($result->getBody()->getContents(), true));
    }

    public function testDeleteالموظفين(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM الموظفين WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $controller = new الموظفينController($this->pdo);
        $result = $controller->deleteالموظفين($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['message' => 'الموظف deleted successfully'], json_decode($result->getBody()->getContents(), true));
    }
}