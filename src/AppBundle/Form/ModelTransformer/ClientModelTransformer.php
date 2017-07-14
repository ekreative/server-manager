<?php

namespace AppBundle\Form\ModelTransformer;

use AppBundle\Entity\Client;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class ClientModelTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Client $value
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
     * @param string $value
     * @return Client
     */
    public function reverseTransform($value)
    {
        if ($value) {
            /**
             * @var Client $client
             */
            $client = $this->em->getRepository(Client::class)->find(['name' => $value]);

            if ($client) {
                return $client;
            }
        }
        return null;
    }
}
