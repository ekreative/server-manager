<?php

namespace AppBundle\Redmine;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class RedmineUserRegisterListener
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $datauser = $this->tokenStorage->getToken()->getUser();
        $user = $this->entityManager->getRepository('AppBundle:User')->find($datauser->getId());

        if ($user) {
            $user->setUsername($datauser->getUsername());
            $user->setFirstName($datauser->getFirstName());
            $user->setLastName($datauser->getLastName());
            $user->setEmail($datauser->getEmail());
            $user->setLastLoginAt($datauser->getLastLoginAt());
            $user->setApiKey($datauser->getApiKey());
            $user->setIsAdmin($datauser->getIsAdmin());
            $this->tokenStorage->getToken()->setUser($user);
        } else {
            $this->entityManager->persist($datauser);
        }

        $this->entityManager->flush();
    }
}
