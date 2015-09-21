<?php

namespace AppBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\SecurityContextInterface;

class SiteAuthor
{

    private $security;

    public function __construct(SecurityContextInterface $security)
    {
        $this->security = $security;
    }

    public function postUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $user = $this->security->getToken()->getUser();
        if ($entity instanceof \AppBundle\Entity\Site) {
            $user = $entityManager->getRepository('AppBundle\Entity\User')->find($user->getId());
            $entity->setEditor($user);
            $entityManager->flush();
        }
    }


    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $user = $this->security->getToken()->getUser();
        if ($entity instanceof \AppBundle\Entity\Site) {
            $user = $entityManager->getRepository('AppBundle\Entity\User')->find($user->getId());
            $entity->setAuthor($user);
            $entity->setEditor($user);
            $entityManager->flush();
        }
    }

}