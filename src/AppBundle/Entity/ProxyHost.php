<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProxyHost
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProxyHost
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="proxyHost")
     */
    private $login;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ProxyHost
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Login $login
     * @return ProxyHost
     */
    public function setLogin(Login $login = null)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return Login
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
