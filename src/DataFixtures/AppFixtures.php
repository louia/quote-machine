<?php

namespace App\DataFixtures;

use App\Entity\Citation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $client = HttpClient::create();
        for ($i = 0; $i < 20; $i++) {
            $response = $client->request('GET', 'https://api.quotable.io/random');
            if ($response->getStatusCode() == "200") {
                $data = json_decode($response->getContent(), TRUE);

                $quote = new Citation();
                $quote->setContent($data["content"]);
                $quote->setMeta($data["author"]);
                $manager->persist($quote);
            }
        }
        $manager->flush();
    }
}
