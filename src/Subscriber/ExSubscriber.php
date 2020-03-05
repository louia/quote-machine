<?php

namespace App\Subscriber;

use App\Entity\User;
use App\Event\UserExpEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserExpEvent::NEW_QUOTE => 'onQuoteNew',
            UserExpEvent::POST_IN_CATG => 'onNewCatg',
            UserExpEvent::LIKE_QUOTE => 'onLike',
            UserExpEvent::LIKE_QUOTE_AUTHOR => 'onLikeAuthor',
        ];
    }

    private function addExpToUser(int $numberofExp, UserExpEvent $event, $like)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $event->getQuote()->getAuthor()->getId()]);
        if (null == $user) {
            $user = $event->getUser();
        }

        if ($like) {
            $user = $event->getUser();
        }

        $user->setExp($user->getExp() + $numberofExp);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function onNewCatg(UserExpEvent $event)
    {
        $this->addExpToUser(120, $event, false);
    }

    public function onQuoteNew(UserExpEvent $event)
    {
        $this->addExpToUser(100, $event, false);
    }

    public function onLike(UserExpEvent $event)
    {
        $this->addExpToUser(25, $event, true);
    }

    public function onLikeAuthor(UserExpEvent $event)
    {
        $this->addExpToUser(200, $event, false);
    }
}
