<?php

namespace AppBundle\Form\ModelTransformer;

use AppBundle\Entity\User;

use AppBundle\Redmine\RedmineUserFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class UserModelTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var RedmineUserFactory
     */
    private $userFactory;

    /**
     * @var RedmineUserFactory
     */
    private $redmineClient;

    public function __construct(ObjectManager $em, RedmineUserFactory $userFactory, $redmineClient)
    {
        $this->em = $em;
        $this->userFactory = $userFactory;
        $this->redmineClient = $redmineClient;
    }

    /**
     * @param User $value
     * @return string
     */
    public function transform($value)
    {
        if ($value) {
            return $value->getId();
        }
        return null;
    }

    /**
     * @param User $value
     * @return User
     */
    public function reverseTransform($value)
    {
        if ($value) {
            $user = $this->em->getRepository(User::class)->find($value);
            if (!$user) {
                $uri = '/users/' . $value . '.json?include=groups';
                $result = \GuzzleHttp\json_decode($this->redmineClient->get($uri)->getBody(), true);

                return $this->userFactory->loadUserByData(array_shift($result), false);
            }
            return $user;
        }
        return null;
    }
}
