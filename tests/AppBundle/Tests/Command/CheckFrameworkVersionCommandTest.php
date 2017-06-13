<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CheckFrameworkVersionCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CheckFrameworkVersionCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $client = static::createClient();
        $client->getContainer()->get('fixture_loader')->load();

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CheckFrameworkVersionCommand($client->getContainer()->get('doctrine'), $client->getContainer()->get('logger')));

        $command = $application->find('app:check-version');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Versions checked.', $output);

    }
}