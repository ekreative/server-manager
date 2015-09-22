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

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $datauser = $this->security->getToken()->getUser();
        $user = $this->entityManager->getRepository('AppBundle:User')->find($datauser->getId());

        if ($user) {
            $user->setUsername($datauser->getUsername());
            $user->setFirstName($datauser->getFirstName());
            $user->setLastName($datauser->getLastName());
            $user->setEmail($datauser->getEmail());
            $user->setLastLoginAt($datauser->getLastLoginAt());
            $user->setApiKey($datauser->getApiKey());
            $user->setIsAdmin($datauser->getIsAdmin());
            $this->security->getToken()->setUser($authUser);
        } else {
            $this->entityManager->persist($datauser);
        }

        $this->entityManager->flush();
    }

}