<?php

namespace AppBundle\Listener;

use AppBundle\Entity\User;
use Ekreative\RedmineLoginBundle\Security\RedmineUser;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManager;

class UserRegisterRedmine
{

    private $entityManager;
    private $security;

    public function __construct(SecurityContextInterface $security, EntityManager $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        /**
         * @var RedmineUser $datauser;
         */
        $datauser = $this->security->getToken()->getUser();
        $user = $this->entityManager->getRepository('AppBundle:User')->find($datauser->getId());
        if(!$user) {
            $user = new User();
        }
        $user->setId($datauser->getId());
        $user->setUsername($datauser->getUsername());
        $user->setFirstName($datauser->getFirstName());
        $user->setLastName($datauser->getLastName());
        $user->setEmail($datauser->getEmail());
        $user->setIsAdmin($datauser->getIsAdmin());
        $user->setCreatedAt($datauser->getCreatedAt());
        $user->setApiKey($datauser->getApiKey());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}