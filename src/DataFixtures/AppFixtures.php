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

                $encoded = urlencode($data["content"]);
                $reponseTranslate = $client->request('GET', 'https://api.mymemory.translated.net/get?q='.$encoded.'&langpair=en|fr');
                if($reponseTranslate->getStatusCode()=="200"){
                    $fr= json_decode($reponseTranslate->getContent(),TRUE);
                    $fr = $fr["responseData"]["translatedText"];
                    $fr = htmlspecialchars_decode($fr, ENT_QUOTES);
                    $quote->setContent($fr);
                    $quote->setMeta($data["author"]);
                    $manager->persist($quote);
//                  dump($fr,$data["content"]);
                }
                else{
                    $quote->setContent($data["content"]);
                    $quote->setMeta($data["author"]);
                    $manager->persist($quote);
                }
            }
        }
        $manager->flush();
    }
}
