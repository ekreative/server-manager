<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Login
 *
 * @ORM\Entity
 */
class Login
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $sshKey;

    /**
     * @var string
     * DNS regex - http://stackoverflow.com/a/10306731/859027
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.)+[a-zA-Z]{2,}$/")
     */
    private $hostname;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min="1", max="65535")
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Url()
     */
    private $url;

    /**
     * @var Site
     *
     * @ORM\OneToOne(targetEntity="Site", inversedBy="adminLogin")
     */
    private $siteAdmin;

    /**
     * @var Site
     *
     * @ORM\OneToOne(targetEntity="Site", inversedBy="databaseLogin")
     */
    private $siteDatabase;

    /**
     * @var Server
     *
     * @ORM\OneToOne(targetEntity="Server", inversedBy="rootLogin")
     */
    private $serverRoot;

    /**
     * @var Server
     *
     * @ORM\OneToOne(targetEntity="Server", inversedBy="hostingLogin")
     */
    private $serverHosting;

    /**
     * @var Server
     *
     * @ORM\OneToOne(targetEntity="Server", inversedBy="userLogin")
     */
    private $serverUser;

    /**
     * @var Server
     *
     * @ORM\OneToOne(targetEntity="Domain", inversedBy="managementLogin")
     */
    private $domainManagement;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     * @return Login
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $password
     * @return Login
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $sshKey
     * @return Login
     */
    public function setSshKey($sshKey)
    {
        $this->sshKey = $sshKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSshKey()
    {
        return $this->sshKey;
    }

    /**
     * @param string $hostname
     * @return Login
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param Site $siteAdmin
     * @return Login
     */
    public function setSiteAdmin(Site $siteAdmin = null)
    {
        $this->siteAdmin = $siteAdmin;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSiteAdmin()
    {
        return $this->siteAdmin;
    }

    /**
     * @param Site $siteDatabase
     * @return Login
     */
    public function setSiteDatabase(Site $siteDatabase = null)
    {
        $this->siteDatabase = $siteDatabase;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSiteDatabase()
    {
        return $this->siteDatabase;
    }

    /**
     * @param Server $serverRoot
     * @return Login
     */
    public function setServerRoot(Server $serverRoot = null)
    {
        $this->serverRoot = $serverRoot;

        return $this;
    }

    /**
     * @return Server
     */
    public function getServerRoot()
    {
        return $this->serverRoot;
    }

    /**
     * @param Server $serverHosting
     * @return Login
     */
    public function setServerHosting(Server $serverHosting = null)
    {
        $this->serverHosting = $serverHosting;

        return $this;
    }

    /**
     * @return Server
     */
    public function getServerHosting()
    {
        return $this->serverHosting;
    }

    /**
     * @param Server $serverUser
     * @return Login
     */
    public function setServerUser(Server $serverUser = null)
    {
        $this->serverUser = $serverUser;

        return $this;
    }

    /**
     * @return Server
     */
    public function getServerUser()
    {
        return $this->serverUser;
    }

    /**
     * @param integer $port
     * @return Login
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param Domain $domainManagement
     * @return Login
     */
    public function setDomainManagement(Domain $domainManagement = null)
    {
        $this->domainManagement = $domainManagement;

        return $this;
    }

    /**
     * @return Domain
     */
    public function getDomainManagement()
    {
        return $this->domainManagement;
    }

    /**
     * @param string $url
     * @return Login
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
