<?php

declare(strict_types=1);

namespace App\Tests;

use App\Trainees;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestTrainees extends TestCase
{
    private Trainees $trainees;
    private MockObject $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->trainees = new Trainees($this->pdo);
    }

    public function testGetAllTrainees(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM trainees')
            ->willReturn($stmt);

        $result = $this->trainees->getAllTrainees();
        $this->assertCount(2, $result);
    }

    public function testGetTraineeById(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM trainees WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainees->getTraineeById(1);
        $this->assertEquals(['id' => 1, 'name' => 'John Doe'], $result);
    }

    public function testCreateTrainee(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO trainees (name) VALUES (?)')
            ->willReturn($stmt);

        $result = $this->trainees->createTrainee('John Doe');
        $this->assertTrue($result);
    }

    public function testUpdateTrainee(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'Jane Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE trainees SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainees->updateTrainee(1, 'Jane Doe');
        $this->assertTrue($result);
    }

    public function testDeleteTrainee(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM trainees WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainees->deleteTrainee(1);
        $this->assertTrue($result);
    }
}