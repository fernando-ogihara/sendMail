<?php

use PHPUnit\Framework\TestCase;

class SendEmailTest extends TestCase 
{
    protected $users;

    public function setUp(): void {
        require_once 'app/sendEmail.php';

        $this->users = [
            ['id' => '1', 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => '2', 'name' => 'Jane Smith', 'email' => 'jane@example']
        ];
    }
    public function testUserFound() {
        $result = sendEmail('admin', $this->users, '1');
        $this->assertEquals('John Doe => john@example.com', $result);
    }
    public function testUserNotFound() {
        $result = sendEmail('admin', $this->users, '3');
        $this->assertEquals('User with ID not found.', $result);
    }
    public function testInvalidIdFormat() {
        $result = sendEmail('admin', $this->users, 'abc');
        $this->assertEquals('Invalid ID format.', $result);
    }
    public function testInvalidEmailFormat() {
        $result = sendEmail('admin', $this->users, '2');
        $this->assertEquals('Invalid email format.', $result);
    }
    public function testInvalidUserRole() {
        $result = sendEmail('ogiharaf', $this->users, '1');
        $this->assertEquals('Permission denied.', $result);
    }
}
