<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Domain
 *
 * @ORM\Entity
 */
class Domain
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
     * DNS regex - http://stackoverflow.com/a/10306731/859027
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/^([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.)+[a-zA-Z]{2,}$/")
     */
    private $name;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="domains")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $site;

    /**
     * @var Server
     *
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="domains")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $server;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="domainManagement", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $managementLogin;

    public function __construct()
    {
        $this->setManagementLogin(new Login(Login::TYPE_SITE));
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Domain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Site $site
     * @return Domain
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Server $server
     * @return Domain
     */
    public function setServer(Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param Login $managementLogin
     * @return Domain
     */
    public function setManagementLogin(Login $managementLogin = null)
    {
        $this->managementLogin = $managementLogin;

        return $this;
    }

    /**
     * @return Login
     */
    public function getManagementLogin()
    {
        return $this->managementLogin;
    }
}
