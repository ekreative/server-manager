<?php

namespace AppBundle\Command;

use AppBundle\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckSiteStatusCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Registry $doctrine, LoggerInterface $logger)
    {
        parent::__construct();

        $this->em = $doctrine->getManager();
        $this->logger = $logger;
    }

    public function configure()
    {
        $this
            ->setName('app:check-site-status')
            ->setDescription('Checks the supported sites for expiration of SLA.')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sites = $this->em->getRepository(Site::class)->findBy(['status' => "supported"]);
        if (!$sites) {
            $this->logger->error('No sites to update.');
            return;
        }

        $i = 0;
        foreach ($sites as $site) {
            if ($site->getSlaEndAt() && $site->getSlaEndAt()  < new \DateTime()) {
                $i++;
                $site->setStatus('unsupported');
            }
        }

        $this->em->flush();
        $this->logger->info($i . "sites updated");
    }
}
