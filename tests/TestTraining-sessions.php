<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestTrainingSessions extends TestCase
{
    private $pdo;
    private $trainingSession;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->trainingSession = new TrainingSessions($this->pdo);
    }

    public function testGetAllTrainingSessions()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Training Session 1'],
                ['id' => 2, 'name' => 'Training Session 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM training_sessions')
            ->willReturn($stmt);

        $result = $this->trainingSession->getAllTrainingSessions();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetTrainingSessionById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Training Session 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM training_sessions WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainingSession->getTrainingSessionById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateTrainingSession()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Training Session']);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO training_sessions (name) VALUES (?)')
            ->willReturn($stmt);

        $result = $this->trainingSession->createTrainingSession(['name' => 'New Training Session']);
        $this->assertTrue($result);
    }

    public function testUpdateTrainingSession()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'Updated Training Session']);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE training_sessions SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainingSession->updateTrainingSession(1, ['name' => 'Updated Training Session']);
        $this->assertTrue($result);
    }

    public function testDeleteTrainingSession()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM training_sessions WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->trainingSession->deleteTrainingSession(1);
        $this->assertTrue($result);
    }
}

class TrainingSessions
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTrainingSessions()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training_sessions');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTrainingSessionById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training_sessions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createTrainingSession($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO training_sessions (name) VALUES (?)');
        $stmt->execute([$data['name']]);
        return $stmt->rowCount() > 0;
    }

    public function updateTrainingSession($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE training_sessions SET name = ? WHERE id = ?');
        $stmt->execute([$data['name'], $id]);
        return $stmt->rowCount() > 0;
    }

    public function deleteTrainingSession($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM training_sessions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}