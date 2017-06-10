<?php

namespace AppBundle\Command;

use AppBundle\Entity\Site;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateJoomlaCurrentVersion extends Command
{

    protected $doctrine;

    protected $logger;

    /**
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(RegistryInterface $doctrine, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        parent::__construct(null);
    }

    public function configure()
    {
        $this
            ->setName('app:update-joomla-version')
            ->setDescription('Update current versions of Joomla sites.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $joomlaSites = $this->doctrine->getRepository(Site::class)->iterateJoomlaSites();

        $batchSize = 50;
        $i = 0;

        foreach ($joomlaSites as $site) {
            /** @var Site $site */
            $site = &$site[0];
            if (($url = $site->getAdminLogin()->getUrl())) {
                $url = parse_url($url)['host'];

                try {
                    $xmlContent = $client
                        ->get($url . '/language/en-GB/en-GB.xml')
                        ->getBody()
                        ->getContents();
                    $content = new \SimpleXMLElement($xmlContent);
                    if ($content) {
                        $site->setFrameworkVersion($content->version);
                        $this->logger->info('Framework version is updated.', [$site->getName()]);
                    }
                    $this->logger->info('Framework version is updated.', [$site->getName() => $content->version]);
                } catch (\Exception $e) {
                    $this->logger->critical('failed to update framework version', ['site name' => $site->getName()]);
                }
            }

            if (($i % $batchSize) === 0) {
                $this->doctrine->getManager()->flush();
                $this->doctrine->getManager()->clear();
            }
            ++$i;
        }

        $this->doctrine->getEntityManager()->flush();
    }

}
