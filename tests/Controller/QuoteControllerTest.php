<?php

namespace App\Tests\Controller;

use App\Entity\Citation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class QuoteControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
//        $kernel = self::bootKernel();

//        $this->entityManager = self::$kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();
    }

    public function testNew()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'louis',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Envoyer')->form();
        $form['quote[content]']->setValue('Symfony Rocks!');
        $form['quote[meta]']->setValue('Louis Chovaneck');
        $client->submit($form);

        $cit = self::$container->get('doctrine')->getManager()->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $this->assertSame('Symfony Rocks!', $cit->getContent());
    }

    public function testNewEdit()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'louis',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Envoyer')->form();
        $form['quote[content]']->setValue('Symfony Rocks!');
        $form['quote[meta]']->setValue('Louis Chovaneck');
        $client->submit($form);

        $cit = self::$container->get('doctrine')->getManager()->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $crawlerr = $client->request('GET', '/quotes/edit/'.$cit->getId());

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawlerr->selectButton('Envoyer')->form();
        $form['quote[content]']->setValue('Symfony 5.0 Rocks!');
        $form['quote[meta]']->setValue('Louis Chovaneck');
        $client->submit($form);

        $cit = self::$container->get('doctrine')->getManager()->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $this->assertSame('Symfony 5.0 Rocks!', $cit->getContent());
    }

    public function testNewDelete()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'louis',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Envoyer')->form();
        $form['quote[content]']->setValue('Symfony Rocks!');
        $form['quote[meta]']->setValue('Louis Chovaneck');
        $client->submit($form);

        $cit = self::$container->get('doctrine')->getManager()->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $this->assertSame('Symfony Rocks!', $cit->getContent());

        $crawler = $client->request('GET', '/quotes/delete/'.$cit->getId());

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $cit = self::$container->get('doctrine')->getManager()->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $this->assertSame(null, $cit);
    }

    public function testNewShow()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'louis',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Envoyer')->form();
        $form['quote[content]']->setValue('Symfony Rocks!');
        $form['quote[meta]']->setValue('Louis Chovaneck');
        $client->submit($form);

        $cit = self::$container->get('doctrine')->getManager()->getRepository(Citation::class)
            ->findOneBy(['meta' => 'Louis Chovaneck']);

        $this->assertSame('Symfony Rocks!', $cit->getContent());

        $crawler = $client->request('GET', '/quotes');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $found = false;
        $numberOfPages = $crawler->filter('ul.pagination-list li')->count();
        for ($i = 1; $i <= $numberOfPages; ++$i) {
            $myQuote = $crawler->filter('p.title')->each(function (Crawler $node, $i) {
                return $node->text();
            });
            for ($ii = 1; $ii <= count($myQuote); ++$ii) {
                if ($myQuote[$ii - 1] == $cit->getContent()) {
                    $find = $myQuote[$ii - 1];
                    $found = true;
                }
            }
            if (!$found) {//Si pas trouver dans la page : page suivante
                $crawler = $client->request('GET', '/quotes?page='.($i + 1));
            }
        }
        $this->assertEquals(true, $found);
    }

    protected function tearDown()
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
//        $this->entityManager->close();
//        $this->entityManager = null;
    }
}
