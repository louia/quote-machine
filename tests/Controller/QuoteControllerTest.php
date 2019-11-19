<?php

namespace App\Tests\Controller;

use App\Entity\Citation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testNew()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Envoyer')->form();
        $form['quote[content]']->setValue('Symfony Rocks!');
        $form['quote[meta]']->setValue('Louis Chovaneck');
        $client->submit($form);

        $cit = $this->entityManager
            ->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $this->assertSame('Symfony Rocks!', $cit->getContent());
    }

    protected function tearDown()
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
