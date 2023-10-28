<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Test Should Display Login Page
     *
     * @return void
     */
    public function testShouldDisplayLoginPage(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $button = $crawler->filter('.btn.btn-success');
        $this->assertEquals(1, count($button));

        $this->assertSelectorTextContains('h1', 'Connexion');

        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('login');
    }

    /**
     * Test Should Be Login Success
     *
     * @return void
     */
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

    /**
     * Test Should Be Login Failed
     *
     * @return void
     */
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

    /**
     * Test Should Be Logout Success
     *
     * @return void
     */
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
