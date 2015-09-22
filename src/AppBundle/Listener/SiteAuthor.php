<?php

namespace AppBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SiteAuthor
{

    protected $container;

    public function __construct(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!$entity instanceof \AppBundle\Entity\User) {
            $user = $entityManager->getRepository('AppBundle\Entity\User')->find($user->getId());
            $entity->setEditor($user);
        }
    }


    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!$entity instanceof \AppBundle\Entity\User) {
            $user = $entityManager->getRepository('AppBundle\Entity\User')->find($user->getId());
            $entity->setAuthor($user);
            $entity->setEditor($user);
        }
    }

}