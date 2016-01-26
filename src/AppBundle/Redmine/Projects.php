<?php

namespace AppBundle\Redmine;

use Doctrine\Common\Cache\Cache;
use Ekreative\RedmineLoginBundle\Client\ClientProvider;
use Ekreative\RedmineLoginBundle\Security\RedmineUser;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Projects
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Cache
     */
    private $cache;

    private $cachePrefix;

    public function __construct(ClientProvider $clientProvider, TokenStorageInterface $tokenStorage, Cache $cache = null)
    {
        if ($tokenStorage->getToken()) {
            /** @var RedmineUser $user */
            $user = $tokenStorage->getToken()->getUser();
            $this->client = $clientProvider->get($user);
            $this->cachePrefix = $user->getId();
        } else {
            throw new \InvalidArgumentException('Cannot access Projects as no user is logged in');
        }
        $this->cache = $cache;
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
        $key = "{$this->cachePrefix}-all-projects";

        if ($this->cache && ($projects = $this->cache->fetch($key))) {
            return $projects;
        }

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
            /** @var Response[] $responses */
            $responses = Promise\unwrap($requests);
            $responseProjects = array_map(function (Response $response) {
                return json_decode($response->getBody(), true)['projects'];
            }, $responses);
            $responseProjects[] = $projects;
            $projects = call_user_func_array('array_merge', $responseProjects);
        }

        usort($projects, function ($projectA, $projectB) {
            return strcasecmp($projectA['name'], $projectB['name']);
        });

        $this->cache && $this->cache->save($key, $projects);
        return $projects;
    }
}
