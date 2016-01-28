<?php

namespace AppBundle\AuthorEditor;

use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AuthorEditorListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        if (($token  = $this->tokenStorage->getToken())) {
            return $token->getUser();
        }
        return null;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof AuthorEditorable && ($user = $this->getUser())) {
            $entity->setEditor($user);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof AuthorEditorable && ($user = $this->getUser())) {
            $entity->setAuthor($user);
            $entity->setEditor($user);
        }
    }
}
