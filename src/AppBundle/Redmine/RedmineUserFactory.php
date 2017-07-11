<?php
/**
 * Created by mcfedr on 1/21/16 12:55
 */

namespace AppBundle\Redmine;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Ekreative\RedmineLoginBundle\Security\RedmineUserFactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;

class RedmineUserFactory extends EntityUserProvider implements RedmineUserFactoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Client
     */
    private $redmineClient;

    /**
     * @var string
     */
    private $groupServerManagers;


    public function __construct(Registry $registry, Client $redmineClient, $groupServerManagers)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $registry->getManager();
        $this->redmineClient = $redmineClient;
        $this->groupServerManagers = $groupServerManagers;
    }

    /**
     * @param array $data
     * @param bool $isAdmin
     * @return User
     */
    public function loadUserByData(array $data, $isAdmin)
    {
        $user = $this->entityManager->getRepository('AppBundle:User')->find($data['id']);

        try {
            // redmine.ekreative.com/users/{user Id}.json?include=groups  <--  sample request for response user {user Id} groups from RedMine
            $groupsResponse = $this->redmineClient->get("/users/{$data['id']}.json?include=groups");
            $groupsData = json_decode($groupsResponse->getBody(), true);

            if ($groupsData && isset($groupsData['user']) && isset($groupsData['user']['groups']) && ($arrayGroups = $groupsData['user']['groups'])) {
                foreach ($arrayGroups as $group) {
                    if ($group['name'] == $this->groupServerManagers) {
                        $isAdmin = true;
                    }
                }
            }
        } catch (RequestException $e) {
            // Failed is admin lookup
        }

        if ($user) {
            $user->updateWithData($data);
            $user->setAdmin($isAdmin);
        } else {
            $user = new User($data, $isAdmin);
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush($user);

        return $user;
    }
}
