<?php

namespace AppBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class AppTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var EntityManager
     */
    protected $em;

    public function setUp()
    {
        $this->client = static::createClient();

        $this->doctrine = static::$kernel->getContainer()
            ->get('doctrine');

        $this->em = $this->doctrine->getManager();

        //$this->runCommand(['command' => 'doctrine:schema:update', '--force' => true]);
    }

    public function tearDown()
    {
        //$this->runCommand(['command' => 'doctrine:schema:drop', '--force' => true]);
        $this->client = null;
    }

    protected function runCommand(array $arguments = [])
    {
        $application = new Application($this->client->getKernel());
        $application->setAutoExit(false);
        $arguments['--quiet'] = true;
        $arguments['--env'] = 'test';

        $input = new ArrayInput($arguments);
        $application->run($input, new ConsoleOutput());
    }
}
