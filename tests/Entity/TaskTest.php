<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
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
             ->setUpdatedAt($datetime)
             ->setCreatedBy($user)
             ->setUpdatedBy($user);

        $this->assertTrue($task->getTitle() === 'titre');
        $this->assertTrue($task->getContent() === 'contenu');
        $this->assertTrue($task->isDone() === true);
        $this->assertTrue($task->getCreatedAt() === $datetime);
        $this->assertFalse($task->getUpdatedAt() === new DateTime());
        $this->assertTrue($task->getCreatedBy() === $user);
        $this->assertTrue($task->getUpdatedBy() === $user);
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
             ->setUpdatedAt($datetime)
             ->setCreatedBy($user)
             ->setUpdatedBy($user);

        $this->assertFalse($task->getTitle() === 'false');
        $this->assertFalse($task->getContent() === 'false');
        $this->assertFalse($task->isDone() === false);
        $this->assertFalse($task->getCreatedAt() === new DateTime());
        $this->assertFalse($task->getUpdatedAt() === new DateTime());
        $this->assertFalse($task->getCreatedBy() === new User());
        $this->assertFalse($task->getUpdatedBy() === new User());
    }

    public function testIsEmpty()
    {
        $task = new Task();

        $this->assertEmpty($task->getTitle());
        $this->assertEmpty($task->getContent());
        $this->assertEmpty($task->isDone());
        $this->assertEmpty($task->getCreatedAt());
        $this->assertEmpty($task->getUpdatedAt());
        $this->assertEmpty($task->getCreatedBy());
        $this->assertEmpty($task->getUpdatedBy());
        $this->assertEmpty($task->getId());
    }
}
