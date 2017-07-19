<?php

namespace AppBundle\Tests;

use AppBundle\Command\TestFixturesLoaderCommand;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

class AppTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Filesystem
     */
    protected $fs;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->fs = new Filesystem();

        $container = $this->client->getContainer();

        $wsd = $container->getParameter('default_db_path');

        if ($this->fs->exists($container->getParameter('default_db_path'))) {
            $this->fs->copy(
                $container->getParameter('default_db_path'),
                $container->getParameter('test_db_path'));
        } else {
            TestFixturesLoaderCommand::runCommand($this->client->getKernel(),
                ['command' => 't:f', '-e' => 'test', '-f' => true]);
        }
    }


    public function tearDown()
    {
        $this->fs->remove($this->client->getContainer()->getParameter('test_db_path'));
        $this->client = null;
        parent::tearDown();
    }

}
