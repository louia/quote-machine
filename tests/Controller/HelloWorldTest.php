<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloWorldTest extends WebTestCase
{
    public function testHelloWorld()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello');

        $this->assertContains(
            'Hello World!',
            $client->getResponse()->getContent()
        );
        $this->assertResponseIsSuccessful();
    }
}
