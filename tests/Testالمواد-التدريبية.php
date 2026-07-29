<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالموادالتدريبية extends TestCase
{
    private MockObject $pdo;
    private MockObject $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetAllالموادالتدريبية(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Material 1'],
                ['id' => 2, 'name' => 'Material 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM المواد_التدريبية')
            ->willReturn($this->statement);

        $result = $this->getAllالموادالتدريبية($this->pdo);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetالموادالتدريبيةById(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->statement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Material 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM المواد_التدريبية WHERE id = ?')
            ->willReturn($this->statement);

        $result = $this->getالموادالتدريبيةById($this->pdo, 1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateالموادالتدريبية(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['Material 1']);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المواد_التدريبية (name) VALUES (?)')
            ->willReturn($this->statement);

        $result = $this->createالموادالتدريبية($this->pdo, 'Material 1');
        $this->assertTrue($result);
    }

    public function testUpdateالموادالتدريبية(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['Material 1', 1]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المواد_التدريبية SET name = ? WHERE id = ?')
            ->willReturn($this->statement);

        $result = $this->updateالموادالتدريبية($this->pdo, 1, 'Material 1');
        $this->assertTrue($result);
    }

    public function testDeleteالموادالتدريبية(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المواد_التدريبية WHERE id = ?')
            ->willReturn($this->statement);

        $result = $this->deleteالموادالتدريبية($this->pdo, 1);
        $this->assertTrue($result);
    }

    private function getAllالموادالتدريبية(PDO $pdo): array
    {
        $statement = $pdo->prepare('SELECT * FROM المواد_التدريبية');
        $statement->execute();
        return $statement->fetchAll();
    }

    private function getالموادالتدريبيةById(PDO $pdo, int $id): array
    {
        $statement = $pdo->prepare('SELECT * FROM المواد_التدريبية WHERE id = ?');
        $statement->execute([$id]);
        return $statement->fetch();
    }

    private function createالموادالتدريبية(PDO $pdo, string $name): bool
    {
        $statement = $pdo->prepare('INSERT INTO المواد_التدريبية (name) VALUES (?)');
        $statement->execute([$name]);
        return $statement->rowCount() > 0;
    }

    private function updateالموادالتدريبية(PDO $pdo, int $id, string $name): bool
    {
        $statement = $pdo->prepare('UPDATE المواد_التدريبية SET name = ? WHERE id = ?');
        $statement->execute([$name, $id]);
        return $statement->rowCount() > 0;
    }

    private function deleteالموادالتدريبية(PDO $pdo, int $id): bool
    {
        $statement = $pdo->prepare('DELETE FROM المواد_التدريبية WHERE id = ?');
        $statement->execute([$id]);
        return $statement->rowCount() > 0;
    }
}