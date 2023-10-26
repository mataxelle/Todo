<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AdminControllerTest extends WebTestCase
{
    public function testShouldDisplayAdminTasksList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/tasks');

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('admin_tasks_list'));

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('admin_tasks_list');

        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }

    public function testShouldDisplayAdminUsersList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/users');

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('admin_users_list'));

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('admin_users_list');

        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

}