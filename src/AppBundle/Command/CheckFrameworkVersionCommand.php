<?php

namespace AppBundle\Command;

use AppBundle\Entity\Framework;
use AppBundle\Entity\HealthCheck;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckFrameworkVersionCommand extends Command
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
          ->setName('app:check-version')
          ->setDescription('Checks the current version of a CMS or Framework.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $healthChecks = $this->doctrine->getRepository(HealthCheck::class)->findAll();

        /** @var HealthCheck $healthCheck */
        foreach ($healthChecks as $healthCheck) {
            try {
                $version = $this->checkVersion($healthCheck->getUrl());
                $healthCheck->getSite()->setFrameworkVersion($version);
                $healthCheck->setLastSyncAt(new \DateTime());

            } catch (\Exception $e) {
                $this->logger->error('Check version error.', [
                    'message' => $e->getMessage()
                ]);
            }

        }

        $this->doctrine->getEntityManager()->flush();
        $this->doctrine->getEntityManager()->clear();
    }

    /**
     * Returns version of a framework
     *
     * @param  string $url
     * @return string
     *
     * @throws \ErrorException
     */
    protected function checkVersion($url)
    {
        $urlParts = parse_url($url);
        $client = new Client([
            'base_uri' => sprintf('%s://%s', $urlParts['scheme'], $urlParts['host'])
        ]);

        $response = $client->request('GET', isset($urlParts['path']) ? $urlParts['path'] : '/');

        if ('application/json' === $response->getHeader('Content-Type')[0]) {
            $result = json_decode($response->getBody(), true);
            if (array_key_exists('version', $result) && array_key_exists('framework', $result)) {
                return $result['version'];
            } else {
                throw new \ErrorException('Missing parameters. Response: ' . $response->getBody());
            }
        } elseif ('text/xml' === $response->getHeader('Content-Type')[0]) {
            $xmlContent = $client
                ->request('GET', '/language/en-GB/en-GB.xml')
                ->getBody()
                ->getContents();
            $content = new \SimpleXMLElement($xmlContent);
            return $content->version;
        } else {
            throw new \ErrorException('Invalid Content-Type. Expected: "application/json", "' . $response->getHeader('Content-Type')[0] . '" is given.');
        }
    }

}
