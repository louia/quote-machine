<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--category' => 'Voyage',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('je suis beau', $output);
    }
}
