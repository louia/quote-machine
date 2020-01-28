<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @Route("/like/add/{id}", name="like")
     *
     * @return JsonResponse
     */
    public function like(Citation $quote)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var User $user */
        $user = $this->getUser();
        $response = new JsonResponse();
        if ($user !== $quote->getAuthor()) {
            $response->setData(['code' => 'ok']);

            $quote->addUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();
        } else {
            $response->setData(['code' => 'notok']);
        }

        return $response;
    }

    /**
     * @Route("/like/remove/{id}", name="dislike")
     *
     * @return JsonResponse
     */
    public function dislike(Citation $quote)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $response = new JsonResponse();

        /** @var User $user */
        $user = $this->getUser();
        if ($user !== $quote->getAuthor()) {
            $response->setData(['code' => 'ok']);
            $quote->removeUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();
        } else {
            $response->setData(['code' => 'notok']);
        }

        return $response;
    }
}
