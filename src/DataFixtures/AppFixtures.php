<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Citation;
use App\Entity\User;
use App\Event\UserExpEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    private $eventDispatcher;

    public function __construct(UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher)
    {
        $this->encoder = $encoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('test@gmail.com');
        $user1->setUsername('louis');
        $user1->setDateAdd();

        $password = $this->encoder->encodePassword($user1, '1234');
        $user1->setPassword($password);

        $manager->persist($user1);

        $user2 = new User();
        $user2->setDateAdd();
        $user2->setEmail('admin@gmail.com');
        $user2->setUsername('admin');
        $user2->setRoles([
           'ROLE_ADMIN',
        ]);

        $password = $this->encoder->encodePassword($user2, '1234');
        $user2->setPassword($password);

        $manager->persist($user2);

        $faker = Faker\Factory::create('fr_FR');
        $categories = [];

        for ($i = 0; $i < 15; ++$i) {
            $categorie = new Categorie();
            $categorie->setName($faker->word());
            $categories[] = $categorie;
            $manager->persist($categorie);
        }
        $categorie = new Categorie();
        $categorie->setName('Voyage');
        $manager->persist($categorie);

        $quote = new Citation();
        $quote->setContent('je suis beau');
        $quote->setMeta('CHOVANECK Louis');
        $quote->setAuthor($user1);
        $quote->addCategorie($categorie);
        $event = new UserExpEvent($quote, $user1);
        $this->eventDispatcher->dispatch($event, UserExpEvent::NEW_QUOTE);
        $manager->persist($quote);

        $quote = new Citation();
        $quote->setContent('L’amour est semblable à une rivière, on y rencontre souvent des obstacles mais le chemin en vaut toujours le détour');
        $quote->setMeta('Laurie Coelho Pereira');
        $quote->setAuthor($user1);
        $quote->addCategorie($categorie);
        $event = new UserExpEvent($quote, $user1);
        $this->eventDispatcher->dispatch($event, UserExpEvent::NEW_QUOTE);
        $manager->persist($quote);

        $client = HttpClient::create();
        for ($i = 0; $i < 20; ++$i) {
            $response = $client->request('GET', 'http://api.quotable.io/random');
            if ('200' == $response->getStatusCode()) {
                $data = json_decode($response->getContent(), true);

                $quote = new Citation();

                $encoded = urlencode($data['content']);
                $responseTranslate = $client->request('GET', 'https://api.mymemory.translated.net/get?q='.$encoded.'&langpair=en|fr');
                if ('200' == $responseTranslate->getStatusCode()) {
                    $fr = json_decode($responseTranslate->getContent(), true);
                    $fr = $fr['responseData']['translatedText'];
                    $fr = htmlspecialchars_decode($fr, ENT_QUOTES);
                    $quote->setContent($fr);
                    $quote->setMeta($data['author']);
                    $quote->addCategorie($categories[mt_rand(0, 14)]);
                    $quote->setAuthor($user2);
                    $event = new UserExpEvent($quote, $user2);
                    $this->eventDispatcher->dispatch($event, UserExpEvent::NEW_QUOTE);
//                    for ($i = 0; $i < mt_rand(0,3); $i++) {
//                        $quote->addCategorie($categories[mt_rand(0,14)]);
//                    }
                    $manager->persist($quote);
                } else {
                    $quote->setContent($data['content']);
                    $quote->setMeta($data['author']);
                    $quote->setAuthor($user2);
                    $quote->addCategorie($categories[mt_rand(0, 14)]);
                    $event = new UserExpEvent($quote, $user2);
                    $this->eventDispatcher->dispatch($event, UserExpEvent::NEW_QUOTE);

                    $manager->persist($quote);
                }
            }
        }
        $manager->flush();
    }
}
