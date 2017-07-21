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

    protected $container;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->fs = new Filesystem();

        $this->container = $this->client->getContainer();

        if ($this->fs->exists($this->container->getParameter('default_db_path'))) {
            $this->fs->copy(
                $this->container->getParameter('default_db_path'),
                $this->container->getParameter('test_db_path'));
        } else {
            TestFixturesLoaderCommand::runCommand($this->client->getKernel(),
                ['command' => 't:f', '-e' => 'test', '-f' => true]);
        }
    }


    public function tearDown()
    {
        $this->fs->remove($this->container->getParameter('test_db_path'));
        $this->client = null;
        $this->container = null;
        parent::tearDown();
    }

}
