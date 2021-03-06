<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Entity\User;
use App\Event\UserExpEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LikeController extends AbstractController
{
    /**
     * @Route("/like/add/{id}", name="like")
     *
     * @return JsonResponse
     */
    public function like(Citation $quote, EventDispatcherInterface $eventDispatcher)
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

            $event = new UserExpEvent($quote, $user);
            $eventDispatcher->dispatch($event, UserExpEvent::LIKE_QUOTE);

            $event = new UserExpEvent($quote, $user);
            $eventDispatcher->dispatch($event, UserExpEvent::LIKE_QUOTE_AUTHOR);
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

    /**
     * @Route("/like/top", name="like_ranking")
     */
    public function rank()
    {
        $likes = $this->getDoctrine()->getRepository(Citation::class)->getOrderLike();

        return $this->render('Like/ordered_like.tml.twig', [
            'likes' => $likes,
        ]);
    }
}
