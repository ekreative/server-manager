<?php
/**
 * Created by mcfedr on 1/21/16 12:55
 */

namespace AppBundle\Redmine;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Ekreative\RedmineLoginBundle\Security\RedmineUserFactoryInterface;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;

use Redmine\Client;

class RedmineUserFactory extends EntityUserProvider implements RedmineUserFactoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Client
     */
    private $redMineClient;

    /**
     * @var string
     */
    private $groupServerManagers;


    public function __construct(Registry $registry, Client $redMineClient, $groupServerManagers)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $registry->getManager();
        $this->redMineClient = $redMineClient;
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

        // redmine.ekreative.com/users/{user Id}.json?include=groups  <--  sample request for response user {user Id} groups from RedMine
        $uri = '/users/' . $data['id'] . '.json?include=groups';
        $arrayGroups = $this->redMineClient->get($uri)['user']['groups'];

        if ($arrayGroups){
            foreach ($arrayGroups as $group) {
                if ($group['name'] == $this->groupServerManagers) {
                    $isAdmin = true;
                }
            }
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
