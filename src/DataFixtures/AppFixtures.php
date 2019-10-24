<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Citation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $categories = [];

        for ($i = 0; $i < 15; ++$i) {
            $categorie = new Categorie();
            $categorie->setName($faker->word());
            $categories[] = $categorie;
            $manager->persist($categorie);
        }
//        $manager->flush();

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
                    $quote->addCategorie($categories[mt_rand(0, 14)]);
//                    for ($i = 0; $i < mt_rand(0,3); $i++) {
//                        $quote->addCategorie($categories[mt_rand(0,14)]);
//                    }
                    $manager->persist($quote);
                } else {
                    $quote->setContent($data['content']);
                    $quote->setMeta($data['author']);
                    $quote->addCategorie($categories[mt_rand(0, 14)]);

                    $manager->persist($quote);
                }
            }
        }
        $manager->flush();
    }
}
