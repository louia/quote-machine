<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @Route("/add/like/{id}", name="like")
     */
    public function like(Citation $quote)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();
        $quote->addUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($quote);
        $entityManager->flush();

        return new Response('ok');
//        return $this->render('like/index.html.twig', [
//            'controller_name' => 'LikeController',
//        ]);
    }

    /**
     * @Route("/add/dislike/{id}", name="dislike")
     */
    public function dislike(Citation $quote)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();
        $quote->removeUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($quote);
        $entityManager->flush();

        return new Response('ok');
//        return $this->render('like/index.html.twig', [
//            'controller_name' => 'LikeController',
//        ]);
    }
}
