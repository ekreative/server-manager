<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ekreative\RedmineLoginBundle\Security\RedmineUser;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends RedmineUser
{
    public function __construct() {

    }
}
