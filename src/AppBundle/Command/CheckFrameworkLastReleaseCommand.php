<?php

namespace AppBundle\Command;

use AppBundle\Entity\Framework;
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
            }
        }
        $this->doctrine->getEntityManager()->flush();
        $this->doctrine->getEntityManager()->clear();
    }
}
