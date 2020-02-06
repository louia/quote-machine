<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Entity\User;
use App\Util\GamificationEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{username}", name="profile")
     */
    public function index(User $user)
    {
        //get all citations
        $nbcit = $this->getDoctrine()->getRepository(User::class)->getAllCitationByuser($user);

        //get 5 left citations
        $quotes = $this->getDoctrine()->getRepository(User::class)->getLast5CitationsByUser($user);

        //get 5 left likes
        $likes = $this->getDoctrine()->getRepository(Citation::class)->getLast5LikesByUser($user);

        $level = GamificationEngine::computeLevelForUser($user);

        $pourcentage = GamificationEngine::computeLevelCompletionForUser($user);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'nbCit' => $nbcit[0][1],
            'quotes' => $quotes,
            'level' => $level,
            'pourcentage' => $pourcentage,
            'likes' => $likes,
        ]);
    }
}
