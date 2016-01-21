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

class RedmineUserFactory extends EntityUserProvider implements RedmineUserFactoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(Registry $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $registry->getManager();
    }

    /**
     * @param array $data
     * @param bool $isAdmin
     * @return User
     */
    public function loadUserByData(array $data, $isAdmin)
    {
        $user = $this->entityManager->getRepository('AppBundle:User')->find($data['id']);

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
