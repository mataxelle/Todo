<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testShouldDisplayLoginPage(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $button = $crawler->filter('.btn.btn-success');
        $this->assertEquals(1, count($button));

        $this->assertSelectorTextContains('h1', 'Connexion');

        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('login');
    }

    public function testShouldBeLoginSuccess(): void
    {
        $urlGenerator = $this->client->getContainer()->get("router");
        $crawler = $this->client->request('GET', $urlGenerator->generate('login'));

        $form = $crawler->filter("form[name=login]")->form([
            'email' => "admin@email.com",
            'password' => "azertyuiop"
        ]);
        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertRouteSame('homepage');

    }

    public function testShouldBeLoginFailed(): void
    {
        $urlGenerator = $this->client->getContainer()->get("router");
        $crawler = $this->client->request('GET', $urlGenerator->generate('login'));

        $form = $crawler->filter("form[name=login]")->form([
            'email' => "admin@email.com",
            'password' => "password"
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
        $this->assertSelectorTextContains("div.alert-danger", "Invalid credentials.");
    }

    public function testShouldBeLogoutSuccess(): void
    {
        $urlGenerator = $this->client->getContainer()->get('router');
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $this->client->loginUser($user);

        $crawler = $this->client->request(Request::METHOD_GET, $urlGenerator->generate('logout'));

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect();

        $this->assertRouteSame('login');
    }
}
