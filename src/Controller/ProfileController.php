<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{username}", name="profile")
     */
    public function index(User $user)
    {
        //get all citation
        $nbcit = $this->getDoctrine()->getRepository(User::class)->getAllCitationByuser($user);

        //get 5 left citation
        $quotes = $this->getDoctrine()->getRepository(User::class)->getLast5CitationsByUser($user);

        $xp = $user->getExp();
        $level = 1;
        $nextlevelxp = 100;
        $currentxp = 0;
        while ($xp >= $nextlevelxp) {
            //$xp = $xp - $nextlevelxp;
            ++$level;
            $currentxp = $user->getExp() - $nextlevelxp;
            $nextlevelxp = $nextlevelxp + $level * 100;
        }
        $difference = $level * 100;
        $pourcentage = $currentxp / $difference * 100;

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'nbCit' => $nbcit[0][1],
            'quotes' => $quotes,
            'level' => $level,
            'pourcentage' => $pourcentage,
        ]);
    }
}
