<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Auth;
use App\Database;

class TestAuth extends TestCase
{
    private $auth;
    private $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->auth = new Auth($this->database);
    }

    public function testLoginSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => $password]);

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
        $this->assertEquals($username, $_SESSION['username']);
    }

    public function testLoginFailure()
    {
        $username = 'test_user';
        $password = 'wrong_password';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => 'test_password']);

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
        $this->assertNull($_SESSION['username']);
    }

    public function testRegisterSuccess()
    {
        $username = 'new_user';
        $password = 'new_password';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(null);

        $this->database->expects($this->once())
            ->method('createUser')
            ->with($username, $password);

        $result = $this->auth->register($username, $password);

        $this->assertTrue($result);
        $this->assertEquals($username, $_SESSION['username']);
    }

    public function testRegisterFailure()
    {
        $username = 'existing_user';
        $password = 'new_password';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username]);

        $result = $this->auth->register($username, $password);

        $this->assertFalse($result);
        $this->assertNull($_SESSION['username']);
    }
}