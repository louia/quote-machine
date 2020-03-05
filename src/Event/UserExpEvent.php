<?php

namespace App\Event;

use App\Entity\Citation;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserExpEvent extends Event
{
    public const NEW_QUOTE = 'quote.new';
    public const POST_IN_CATG = 'catg.new';
    public const LIKE_QUOTE = 'quote.like';
    public const LIKE_QUOTE_AUTHOR = 'quote.like.author';

    protected $quote;
    protected $user;

    public function __construct(Citation $quote, User $user)
    {
        $this->quote = $quote;
        $this->user = $user;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
