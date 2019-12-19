<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Citation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('test@gmail.com');
        $user1->setUsername('louis');

        $password = $this->encoder->encodePassword($user1, '1234');
        $user1->setPassword($password);

        $manager->persist($user1);

        $user2 = new User();
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
        $categories[] = $categorie;
        $manager->persist($categorie);

        $client = HttpClient::create();
        for ($i = 0; $i < 20; ++$i) {
            $response = $client->request('GET', 'https://api.quotable.io/random');
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
                    $quote->addCategorie($categories[mt_rand(0, 15)]);
                    $quote->setAuthor($user2);
//                    for ($i = 0; $i < mt_rand(0,3); $i++) {
//                        $quote->addCategorie($categories[mt_rand(0,14)]);
//                    }
                    $manager->persist($quote);
                } else {
                    $quote->setContent($data['content']);
                    $quote->setMeta($data['author']);
                    $quote->setAuthor($user2);
                    $quote->addCategorie($categories[mt_rand(0, 15)]);

                    $manager->persist($quote);
                }
            }
        }
        $manager->flush();
    }
}
