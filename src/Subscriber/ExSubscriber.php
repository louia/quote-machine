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
        ];
    }

    public function onNewCatg(UserExpEvent $event)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $event->getQuote()->getAuthor()->getId()]);
        if (null == $user) {
            $user = $event->getUser();
        }

        $user->setExp($user->getExp() + 120);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function onQuoteNew(UserExpEvent $event)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $event->getQuote()->getAuthor()->getId()]);
        if (null == $user) {
            $user = $event->getUser();
        }

        $user->setExp($user->getExp() + 100);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
