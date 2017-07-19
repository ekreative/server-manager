<?php

namespace AppBundle\Command;

use AppBundle\Entity\Framework;
use GuzzleHttp\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppTestCase;

class CheckFrameworkLastReleaseCommandTest extends AppTestCase
{

    public function testExecute()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CheckFrameworkLastReleaseCommand($this->client->getContainer()->get('doctrine'), $this->client->getContainer()->get('logger')));

        $command = $application->find('app:check-last-release');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $frameworks = $this->client->getContainer()->get('doctrine')->getRepository(Framework::class)->findAll();
        $guzzle = new Client();
        $output = $commandTester->getDisplay();
        /** @var Framework $framework */
        foreach ($frameworks as $framework) {
            switch ($framework->getKey()) {
                case Framework::DRUPAL_7:
                    $versions = $guzzle
                        ->get('https://updates.drupal.org/release-history/drupal/7.x')
                        ->getBody()
                        ->getContents();
                    $content = new \SimpleXMLElement($versions);
                    $content->releases->release->version;
                    $this->assertEquals($content->releases->release->version, $framework->getCurrentVersion());
                    $this->assertContains(Framework::DRUPAL_7.' version updated.', $output);
                    break;
                case Framework::DRUPAL_8:
                    $versions = $guzzle
                        ->get('https://updates.drupal.org/release-history/drupal/8.x')
                        ->getBody()
                        ->getContents();
                    $content = new \SimpleXMLElement($versions);
                    $this->assertEquals($content->releases->release->version, $framework->getCurrentVersion());
                    $this->assertContains(Framework::DRUPAL_8.' version updated.', $output);
                    break;
                case Framework::SYMFONY:
                    $versions = json_decode($guzzle
                        ->get('https://packagist.org/p/symfony/symfony.json')
                        ->getBody()
                        ->getContents(), true);
                    end($versions['packages']['symfony/symfony']);
                    $lastVersion = key($versions['packages']['symfony/symfony']);
                    $this->assertEquals($lastVersion, $framework->getCurrentVersion());
                    $this->assertContains(Framework::SYMFONY.' version updated.', $output);
                    break;
                case Framework::WORDPRESS:
                    $versions = json_decode($guzzle
                        ->get('https://api.wordpress.org/core/version-check/1.7/')
                        ->getBody()
                        ->getContents(), true);
                    $lastVersion = $versions['offers'][0]['current'];
                    $this->assertEquals($lastVersion, $framework->getCurrentVersion());
                    $this->assertContains(Framework::WORDPRESS.' version updated.', $output);
                    break;
                case Framework::JOOMLA:
                    $versions = $guzzle
                        ->get('http://update.joomla.org/core/list.xml')
                        ->getBody()
                        ->getContents();
                    $content = new \SimpleXMLElement($versions);
                    $lastVersion = $content->extension[$content->count() - 1]['version'];
                    $this->assertEquals($lastVersion, $framework->getCurrentVersion());
                    $this->assertContains(Framework::JOOMLA.' version updated.', $output);
                    break;
            }
        }

    }

}