<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LoginRepository extends EntityRepository
{
    public function getProxyHost()
    {
        return false;
    }
}
