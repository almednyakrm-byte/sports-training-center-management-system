<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمراكز_التدريب extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetمراكز_التدريب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مراكز_التدريب')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'مركز التدريب 1']]);

        $result = $this->getمراكز_التدريب();
        $this->assertEquals([['id' => 1, 'name' => 'مركز التدريب 1']], $result);
    }

    public function testPostمراكز_التدريب()
    {
        $data = ['name' => 'مركز التدريب 2'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مراكز_التدريب (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(2);

        $result = $this->postمراكز_التدريب($data);
        $this->assertEquals(2, $result);
    }

    public function testPutمراكز_التدريب()
    {
        $id = 1;
        $data = ['name' => 'مركز التدريب 1 Updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مراكز_التدريب SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putمراكز_التدريب($id, $data);
        $this->assertEquals(true, $result);
    }

    public function testDeleteمراكز_التدريب()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مراكز_التدريب WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteمراكز_التدريب($id);
        $this->assertEquals(true, $result);
    }

    private function getمراكز_التدريب()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM مراكز_التدريب');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postمراكز_التدريب($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO مراكز_التدريب (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    private function putمراكز_التدريب($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE مراكز_التدريب SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteمراكز_التدريب($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM مراكز_التدريب WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}