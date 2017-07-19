<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CheckFrameworkVersionCommand;
use AppBundle\Tests\AppTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CheckFrameworkVersionCommandTest extends AppTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CheckFrameworkVersionCommand($this->client->getContainer()->get('doctrine'), $this->client->getContainer()->get('logger')));

        $command = $application->find('app:check-version');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Versions checked.', $output);

    }
}