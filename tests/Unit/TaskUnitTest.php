<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class PostUnitTest extends TestCase
{
    public function testIsTrue()
    {
        $task = new Task();
        $user = new User();
        $datetime = new DateTime();

        $task->setTitle('titre')
             ->setContent('contenu')
             ->setIsDone(true)
             ->setCreatedAt($datetime)
             ->setUser($user);

        $this->assertTrue($task->getTitle() === 'titre');
        $this->assertTrue($task->getContent() === 'contenu');
        $this->assertTrue($task->isDone() === true);
        $this->assertTrue($task->getCreatedAt() === $datetime);
        $this->assertTrue($task->getUser() === $user);
    }

    public function testIsFalse()
    {
        $task = new Task();
        $user = new User();
        $datetime = new DateTime();

        $task->setTitle('titre')
             ->setContent('contenu')
             ->setIsDone(true)
             ->setCreatedAt($datetime)
             ->setUser($user);

        $this->assertFalse($task->getTitle() === 'false');
        $this->assertFalse($task->getContent() === 'false');
        $this->assertFalse($task->isDone() === false);
        $this->assertFalse($task->getCreatedAt() === new DateTime());
        $this->assertFalse($task->getUser() === new User());
    }

    public function testIsEmpty()
    {
        $task = new Task();

        $this->assertEmpty($task->getTitle());
        $this->assertEmpty($task->getContent());
        $this->assertEmpty($task->isDone());
        $this->assertEmpty($task->getCreatedAt());
        $this->assertEmpty($task->getUser());
        $this->assertEmpty($task->getId());
    }
}
