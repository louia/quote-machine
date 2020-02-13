<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloWorldTest extends WebTestCase
{
    public function testHelloWorld()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello');

        $this->assertStringContainsString(
            'Hello World!',
            $client->getResponse()->getContent()
        );
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html body', 'Hello World!');
    }

    public function testHelloWorldWithParams()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/louis');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html body', 'Hello Louis !');
    }
}
