<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use App\Trainers;

class TestTrainers extends TestCase
{
    private $trainer;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->trainer = new Trainers($this->pdo);
    }

    public function testGetTrainers()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe']
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM trainers')
            ->willReturn($stmt);

        $result = $this->trainer->getTrainers();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetTrainerById()
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
            ->with('SELECT * FROM trainers WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainer->getTrainerById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateTrainer()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO trainers (name) VALUES (?)')
            ->willReturn($stmt);

        $result = $this->trainer->createTrainer(['name' => 'John Doe']);
        $this->assertTrue($result);
    }

    public function testUpdateTrainer()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE trainers SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainer->updateTrainer(1, ['name' => 'John Doe']);
        $this->assertTrue($result);
    }

    public function testDeleteTrainer()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM trainers WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainer->deleteTrainer(1);
        $this->assertTrue($result);
    }
}