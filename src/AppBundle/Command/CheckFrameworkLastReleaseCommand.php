<?php

namespace AppBundle\Command;

use AppBundle\Entity\Framework;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckFrameworkLastReleaseCommand extends Command
{
    protected $doctrine;

    protected $logger;

    public function __construct(RegistryInterface $doctrine, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        parent::__construct(null);
    }

    public function configure()
    {
        $this
            ->setName('app:check-last-release')
            ->setDescription('Checks the last release of frameworks.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $frameworks = $this->doctrine->getRepository(Framework::class)->findAll();
        $client = new Client();

        /** @var Framework $framework */
        foreach ($frameworks as $framework) {
            switch ($framework->getKey()) {
                case Framework::DRUPAL_7:
                    $xmlContent = file_get_contents('https://updates.drupal.org/release-history/drupal/7.x');
                    $content = new \SimpleXMLElement($xmlContent);
                    $framework->setCurrentVersion($content->releases->release->version);
                    break;
                case Framework::DRUPAL_8:
                    $xmlContent = file_get_contents('https://updates.drupal.org/release-history/drupal/8.x');
                    $content = new \SimpleXMLElement($xmlContent);
                    $framework->setCurrentVersion($content->releases->release->version);
                    break;
                case Framework::SYMFONY:
                    $versions = json_decode($client
                        ->get('https://packagist.org/p/symfony/symfony.json')
                        ->getBody()
                        ->getContents(), true);
                    end($versions['packages']['symfony/symfony']);
                    $lastVersion = key($versions['packages']['symfony/symfony']);
                    $framework->setCurrentVersion($lastVersion);
                    break;
                case Framework::WORDPRESS:
                    $versions = json_decode($client
                        ->get('https://api.wordpress.org/core/version-check/1.7/')
                        ->getBody()
                        ->getContents(), true);
                    $lastVersion = $versions['offers'][0]['current'];
                    $framework->setCurrentVersion($lastVersion);
                    break;
                case Framework::JOOMLA:
                    $xmlContent = file_get_contents('http://update.joomla.org/core/list.xml');
                    $content = new \SimpleXMLElement($xmlContent);
                    $lastVersion = $content->extension[$content->count() -1]['version'];
                    $framework->setCurrentVersion($lastVersion);
                    break;
            }
        }
        $this->doctrine->getEntityManager()->flush();
        $this->doctrine->getEntityManager()->clear();
    }
}
