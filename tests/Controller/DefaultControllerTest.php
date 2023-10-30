<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{    
    /**
     * Test Should Display Homepage
     *
     * @return void
     */
    public function testShouldDisplayHomepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Bienvenue sur ToDo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }
}
