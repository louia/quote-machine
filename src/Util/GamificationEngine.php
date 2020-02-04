<?php

use App\Entity\User;

class GamificationEngine
{
    public static function computeLevelForUser(User $user)
    {
        $xp = $user->getExp();
        $level = 1;
        $nextlevelxp = 100;
        while ($xp >= $nextlevelxp) {
            ++$level;
            $nextlevelxp = $nextlevelxp + $level * 100;
        }

        return $level;
    }

    public static function computeLevelCompletionForUser(User $user): int
    {
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

        return $pourcentage;
    }
}
