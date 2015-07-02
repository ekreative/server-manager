<?php

namespace AppBundle\Redmine;

use Ekreative\RedmineLoginBundle\Client\ClientProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Projects
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(ClientProvider $clientProvider, TokenStorageInterface $tokenStorage)
    {
        if ($tokenStorage->getToken()) {
            $this->client = $clientProvider->get($tokenStorage->getToken()->getUser());
        } else {
            throw new \InvalidArgumentException('Cannot access Projects as no user is logged in');
        }
    }

    /**
     * @param string $name
     * @return array
     */
    public function getProjectByName($name)
    {
        foreach ($this->getAllProjects() as $project) {
            if ($project['name'] == $name) {
                return $project;
            }
        }
        return null;
    }

    public function getAllProjects()
    {
        $first = json_decode($this->client->get('projects.json', [
            'query' => [
                'limit' => 100
            ]
        ])->getBody(), true);
        $projects = $first['projects'];

        if ($first['total_count'] > 100) {
            $requests = [];
            for ($i = 100; $i < $first['total_count']; $i += 100) {
                $requests[] = $this->client->getAsync('projects.json', [
                    'query' => [
                        'limit' => 100,
                        'offset' => $i
                    ]
                ]);
            }
            $responses = Promise\unwrap($requests);
            foreach ($responses as $response) {
                $projects = array_merge($projects, json_decode($response->getBody(), true)['projects']);
            }
        }

        return $projects;
    }
}
