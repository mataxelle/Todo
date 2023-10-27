<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UseControllerTest extends WebTestCase
{
    /**
     * not working
     */
    public function testShouldBeRegisterSuccess(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('register'));

        // Should change the user name and email every test
        $form = $crawler->filter("form[name=register_form]")->form([
            'register_form[name]' => "Mimy Donde",
            'register_form[email]' => "usertest1@email.com",
            'register_form[password][first]' => "azertyuiop",
            'register_form[password][second]' => "azertyuiop"
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('login');
    }

    public function testShouldEditUser(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $profile = $entityManager->getRepository(User::class)->findOneBy([
            'id' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('user_edit', ['id' => $profile->getId()])
        );

        $form = $crawler->filter('form[name=user_edit_form]')->form([
            'user_edit_form[name]' => "Patricia Boulay Demaison",
            'user_edit_form[email]' => "user0@email.com"
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! Le profil a bien été modifié');
        $this->assertRouteSame('homepage');
    }

    public function testShouldEditUserPassword(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class, 2);

        $password = $entityManager->getRepository(User::class)->findOneBy([
            'id' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('user_edit_password', ['id' => $password->getId()])
        );

        $form = $crawler->filter('form[name=password_edit_form]')->form([
            'password_edit_form[password][first]' => "password",
            'password_edit_form[password][second]' => "password"
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Superbe ! Le mot de passe a bien été modifié');
        $this->assertRouteSame('homepage');
    }
}