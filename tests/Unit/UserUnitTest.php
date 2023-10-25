<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user->setName('name')
             ->setEmail('true@test.com')
             ->setPassword('password')
             ->setRoles(['ROLE_TEST']);

        $this->assertTrue($user->getName() === 'name');
        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getUserIdentifier() === 'true@test.com');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getRoles() === ['ROLE_TEST', 'ROLE_USER']);
    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user->setName('name')
             ->setEmail('true@test.com')
             ->setPassword('password');

        $this->assertFalse($user->getName() === 'false');
        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getUserIdentifier() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'false');
    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getName());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getUserIdentifier());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getId());
    }

    public function testGetAddRemoveTask() {
        $user = new User();
        $task = new Task();

        $this->assertEmpty($user->getTasks());

        $user->addTask(($task));
        $this->assertContains($task, $user->getTasks());

        $user->removeTask(($task));
        $this->assertEmpty($user->getTasks());
    }
}
