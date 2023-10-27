<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function testShouldCreateTask(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));

        $form = $crawler->filter('form[name=task_form]')->form([
            'task_form[title]' => "Nouvelle task",
            'task_form[content]' => "Task description nouvelle"
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! La tâche a bien été ajoutée.');
        $this->assertRouteSame('task_list', ['id' => $user->getId()]);
    }

    public function testShouldDisplayUsertasksList(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_list', ['id' => $user->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list', ['id' => $user->getId()]);
    }

    public function testShouldDisplayUsertasksDoneList(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('done_task_list', ['id' => $user->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('done_task_list', ['id' => $user->getId()]);
    }

    public function testShouldEditTask(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $task = $entityManager->getRepository(Task::class)->findOneBy([
            'createdBy' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_edit', ['id' => $task->getId()])
        );

        $form = $crawler->filter('form[name=task_form]')->form([
            'task_form[title]' => "First task update",
            'task_form[content]' => "Task description update"
        ]);
                $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! La tâche a bien été modifiée.');
        $this->assertRouteSame('task_list', ['id' => $user->getId()]);
    }

    public function testShouldDeleteTask(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $task = $entityManager->getRepository(Task::class)->findOneBy([
            'createdBy' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_delete', ['id' => $task->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! La tâche a bien été supprimée.');
        $this->assertRouteSame('task_list', ['id' => $user->getId()]);
    }
}
