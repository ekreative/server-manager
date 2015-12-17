<?php

namespace AppBundle\AuthorEditor;

use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthorEditorListener
{

    protected $container;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof AuthorEditorable) {
//            $user = $this->getUser();
//            $entity->setEditor($user);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
//        $userState = $this->container->get('doctrine')->getEntityManager()->getUnitOfWork()->getEntityState($this->getUser());
        if ($entity instanceof AuthorEditorable) {
//            var_dump(get_class($entity), $entity->getId(), $userState);
//            $user = $this->getUser();
//            $entity->setAuthor($user);
//            $entity->setEditor($user);
        }
    }
}
