<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ekreative\RedmineLoginBundle\Security\RedmineUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends RedmineUser
{
    public function __toString()
    {
        return $this->username;
    }
}
