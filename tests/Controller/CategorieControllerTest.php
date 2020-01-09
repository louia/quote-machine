<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategorieControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
    }

    public function testNew()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/categorie/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Créer')->form();
        $form['categorie[name]']->setValue('LifeStyle');
        $form['categorie[imageFile][file]']->upload('150.png');
        $form['categorie[citations][0]']->tick();
        $client->submit($form);

        $cat = self::$container->get('doctrine')->getManager()->getRepository(Categorie::class)
            ->findOneBy(['name' => 'LifeStyle']);
        $citations = $cat->getCitations();
        $txt = $citations[0]->getContent();

        $this->assertSame('je suis beau', $txt);
    }

    public function testNewEdit()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/categorie/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Créer')->form();
        $form['categorie[name]']->setValue('LifeStyle');
        $form['categorie[imageFile][file]']->upload('150.png');
        $form['categorie[citations][0]']->tick();
        $client->submit($form);

        $cat = self::$container->get('doctrine')->getManager()->getRepository(Categorie::class)
            ->findOneBy(['name' => 'LifeStyle']);

        $citations = $cat->getCitations();
        $txt = $citations[0]->getContent();

        $this->assertSame('je suis beau', $txt);

        $crawlerr = $client->request('GET', '/categorie/edit/'.$cat->getSlug());

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawlerr->selectButton('Editer')->form();
        $form['categorie[name]']->setValue('LifeStyle!');
        $client->submit($form);

        $cat = self::$container->get('doctrine')->getManager()->getRepository(Categorie::class)
            ->findOneBy(['id' => $cat->getId()]);

        $this->assertSame('LifeStyle!', $cat->getName());
    }

    public function testNewDelete()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => '1234',
        ]);
        $client->followRedirects();
        $crawler = $client->request('GET', '/categorie/new');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Créer')->form();
        $form['categorie[name]']->setValue('LifeStyle');
        $form['categorie[imageFile][file]']->upload('150.png');
        $form['categorie[citations][0]']->tick();
        $client->submit($form);

        $cat = self::$container->get('doctrine')->getManager()->getRepository(Categorie::class)
            ->findOneBy(['name' => 'LifeStyle']);

        $citations = $cat->getCitations();
        $txt = $citations[0]->getContent();

        $this->assertSame('je suis beau', $txt);

        $crawler = $client->request('GET', '/categorie/delete/'.$cat->getSlug());

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $cat = self::$container->get('doctrine')->getManager()->getRepository(Categorie::class)
            ->findOneBy(['name' => 'LifeStyle']);

        $this->assertSame(null, $cat);
    }

    protected function tearDown()
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
    }
}
