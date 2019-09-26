<?php
// src/Controller/QuoteController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class QuoteController extends AbstractController
{
    /**
     * @Route("/quotes", name="quotes")
     */
    public function index()
    {
        $quote1=[
            "content" => "Sire, Sire ! On en a gros !",
            "meta" => "Perceval, Livre II, Les Exploités"
        ];

        $quote2=[
            "content" => "Excusez ! Y a moyen de vous entretenir deux secondes ? [...] C'est au sujet que ce matin je suis passé devant vos enclos, et j'ai vu que vous aviez une jolie petite poule blanche ! [...] Une jolie petite poule vous voyez, le bel animal [...] C'est au sujet qu'en fait c'est la mienne, et que vous allez prendre un pain dans la tête, mais quelque chose de violent !",
            "meta" => "Guethenoc, Livre III, 53 : Feue La Poule de Guethenoc"
        ];
        $quote3=[
            "content" => "2500 pièces d'or ???! Eh... eh... c'est un blague? 2500 pièces d'or, mais ou voulez vous que j'trouve 2500 pièces d'or, dans l'cul d'une vache ?!",
            "meta" => "Seigneur Jacca, Livre I, 21 : La taxe militaire"
        ];
        $quote4=[
            "content" => "Une pluie de pierres en intérieur donc ! Je vous prenais pour un pied de chaise mais vous êtes un précurseur en fait !",
            "meta" => "Élias de Kelliwic'h, Livre IV, Le Privilégié"
        ];
        $quote5=[
            "content" => "Et qu'est-ce qui font-ils, au gouvernement ? Y's'roucent les poules ! Y's'poulent les rouces ! (Guethenoc : Y's'roulent les pouces !) Voilà, mieux !",
            "meta" => "Roparzh, Livre IV, 53 : Vox populi III"
        ];
        $quotes = [$quote1,$quote2,$quote3,$quote4,$quote5];

        return $this->render('quotes.html.twig', [
            'quotes' => $quotes
        ]);
    }

}