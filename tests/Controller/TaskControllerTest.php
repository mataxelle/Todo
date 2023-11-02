<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    /**
     * Test Should CreateTask
     *
     * @return void
     */
    public function testShouldCreateTask(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));

        $form = $crawler->filter('form[name=task_form]')->form([
                'task_form[title]'   => "Mardi",
                'task_form[content]' => "Faire une nouvelle tâche encore"
            ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! La tâche a bien été ajoutée.');
        $this->assertRouteSame('task_list', ['id' => $user->getId()]);
    }

    /**
     * Test Should Display one task
     *
     * @return void
     */
    public function testShouldDisplayOneTask(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 3);

        $task = $entityManager->getRepository(Task::class)->findOneBy(
            [
                'createdBy' => $user
            ]);

        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_show', ['id' => $task->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_show', ['id' => $task->getId()]);
    }

    /**
     * Test Should Display User tasksList
     *
     * @return void
     */
    public function testShouldDisplayUsertasksList(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET,
            $urlGenerator->generate('task_list', ['id' => $user->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list', ['id' => $user->getId()]);
    }

    /**
     * Test Should Display User tasks DoneList
     *
     * @return void
     */
    public function testShouldDisplayUsertasksDoneList(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('done_task_list', ['id' => $user->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('done_task_list', ['id' => $user->getId()]);
    }

    /**
     * Test Should Edit Task
     *
     * @return void
     */
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
            'task_form[title]'   => "Luni lundi update",
            'task_form[content]' => "encore Update test"
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! La tâche a bien été modifiée.');
        $this->assertRouteSame('task_show', ['id' => $task->getId()]);
    }

    /**
     * Test Should Finish Task
     *
     * @return void
     */
    public function testShouldFinishTask(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $task = $entityManager->getRepository(Task::class)->findOneBy([
                'createdBy' => $user
            ]);

        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_toggle', ['id' => $task->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', $task->isDone() ? sprintf('La tâche %s a bien été marquée : faite.', $task->getTitle()) : sprintf('La tâche %s a bien été marquée : à faire.', $task->getTitle()));
        $this->assertRouteSame('task_show', ['id' => $task->getId()]);
    }

    /**
     * Test Should Delete Task
     *
     * @return void
     */
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

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_delete', ['id' => $task->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! La tâche a bien été supprimée.');
        $this->assertRouteSame('homepage');
    }
}
