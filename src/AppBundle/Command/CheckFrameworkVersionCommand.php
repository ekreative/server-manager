<?php

namespace AppBundle\Command;

use AppBundle\Entity\HealthCheck;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
            $version = $this->checkVersion($healthCheck->getUrl());
            if ($version) {
                $healthCheck->getSite()->setFrameworkVersion($version);
                $healthCheck->setLastSyncAt(new \DateTime());
            }
        }

        $this->doctrine->getEntityManager()->flush();
        $this->doctrine->getEntityManager()->clear();
    }

    /**
     * @param $url
     *
     * @return bool|mixed
     */
    protected function checkVersion($url)
    {
        $urlParts = parse_url($url);
        try {
            $client = new Client(['base_uri' => sprintf('%s://%s', $urlParts['scheme'], $urlParts['host'])]);
            $response  = $client->request('GET', isset($urlParts['path']) ? $urlParts['path'] : '/');

            if ('application/json' === $response->getHeader('Content-Type')[0]) {
                $result = json_decode($response->getBody(), true);
                if (array_key_exists('version', $result)) {
                    return $result['version'];
                }
            }

            $this->logger->error('Check version error', [
                'url' => $url,
                'response' => $response->getBody(),
            ]);
        } catch (RequestException $e) {
            $this->logger->error('Check version error', [
                'url' => $url,
                'request' => \GuzzleHttp\Psr7\str($e->getRequest()),
                'response' => \GuzzleHttp\Psr7\str($e->getResponse()),
            ]);
        }

        return false;
    }

}
