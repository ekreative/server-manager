<?php

namespace AppBundle\Command;

use AppBundle\Entity\Framework;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);
        /** @var Framework $framework */
        foreach ($frameworks as $framework) {
            switch ($framework->getKey()) {
                case Framework::DRUPAL_7:
                    try {
                        $versions = $client
                            ->get('https://updates.drupal.org/release-history/drupal/7.x')
                            ->getBody()
                            ->getContents();
                        $content = new \SimpleXMLElement($versions);
                        $framework->setCurrentVersion($content->releases->release->version);
                        $this->logger->info('Framework latest release is up to date.', ['framework' => $framework->getName()]);
                        $io->success(Framework::DRUPAL_7 .' version updated.');
                    } catch (\Exception $e) {
                        $this->logger->critical('Failed to load latest release', ['framework' => $framework->getName()]);
                    }
                    break;
                case Framework::DRUPAL_8:
                    try {
                        $versions = $client
                            ->get('https://updates.drupal.org/release-history/drupal/8.x')
                            ->getBody()
                            ->getContents();
                        $content = new \SimpleXMLElement($versions);
                        $framework->setCurrentVersion($content->releases->release->version);
                        $this->logger->info('Framework latest release is up to date.', ['framework' => $framework->getName()]);
                        $io->success(Framework::DRUPAL_8 .' version updated.');
                    } catch (\Exception $e) {
                        $this->logger->critical('Failed to load latest release', ['framework' => $framework->getName()]);
                    }
                    break;
                case Framework::SYMFONY:
                    try {
                        $versions = json_decode($client
                            ->get('https://packagist.org/p/symfony/symfony.json')
                            ->getBody()
                            ->getContents(), true);
                        end($versions['packages']['symfony/symfony']);
                        $lastVersion = key($versions['packages']['symfony/symfony']);
                        $framework->setCurrentVersion($lastVersion);
                        $this->logger->info('Framework latest release is up to date.', ['framework' => $framework->getName()]);
                        $io->success(Framework::SYMFONY .' version updated.');
                    } catch (\Exception $e) {
                        $this->logger->critical('Failed to load latest release', ['framework' => $framework->getName()]);
                    }
                    break;
                case Framework::WORDPRESS:
                    try {
                        $versions = json_decode($client
                            ->get('https://api.wordpress.org/core/version-check/1.7/')
                            ->getBody()
                            ->getContents(), true);
                        $lastVersion = $versions['offers'][0]['current'];
                        $framework->setCurrentVersion($lastVersion);
                        $this->logger->info('Framework latest release is up to date.', ['framework' => $framework->getName()]);
                        $io->success(Framework::WORDPRESS .' version updated.');
                    } catch (\Exception $e) {
                        $this->logger->critical('Failed to load latest release', ['framework' => $framework->getName()]);
                    }
                    break;
                case Framework::JOOMLA:
                    try {
                        $versions = $client
                            ->get('http://update.joomla.org/core/list.xml')
                            ->getBody()
                            ->getContents();
                        $content = new \SimpleXMLElement($versions);
                        $lastVersion = $content->extension[$content->count() -1]['version'];
                        $framework->setCurrentVersion($lastVersion);
                        $this->logger->info('Framework latest release is up to date.', ['framework' => $framework->getName()]);
                        $io->success(Framework::JOOMLA .' version updated.');
                    } catch (\Exception $e) {
                        $this->logger->critical('Failed to load latest release', ['framework' => $framework->getName()]);
                    }
                    break;
            }
        }
        $this->doctrine->getManager()->flush();
        $this->doctrine->getManager()->clear();
    }
}
