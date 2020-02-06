<?php

namespace App\Tests\Util;

use App\Entity\User;
use App\Util\GamificationEngine;
use PHPUnit\Framework\TestCase;

class GamificationEngineTest extends TestCase
{
    public function testComputeLevelForUser()
    {
        $user = new User();
        $user->setExp('0');

        $result = GamificationEngine::computeLevelForUser($user);
        $this->assertEquals('1', $result);

        $user->setExp('100');
        $result = GamificationEngine::computeLevelForUser($user);
        $this->assertEquals('2', $result);

        $user->setExp('300');
        $result = GamificationEngine::computeLevelForUser($user);
        $this->assertEquals('3', $result);

        $user->setExp('600');
        $result = GamificationEngine::computeLevelForUser($user);
        $this->assertEquals('4', $result);
    }

    public function testComputeLevelCompletionForUser()
    {
        $user = new User();
        $user->setExp('0');

        $result = GamificationEngine::computeLevelCompletionForUser($user);
        $this->assertEquals('0', $result);

        $user->setExp('200');
        $result = GamificationEngine::computeLevelCompletionForUser($user);
        $this->assertEquals('50', $result);
    }
}
