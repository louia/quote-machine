<?php

namespace App\Security\Voter;

use App\Entity\Citation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CitationVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['quotes_edit', 'quotes_delete'])
            && $subject instanceof \App\Entity\Citation;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        /** @var Citation $citation */
        $citation = $subject;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
                case 'quotes_delete':
                case 'quotes_edit':
                    return $user === $citation->getAuthor();
                    break;
            }

        return false;
    }
}
