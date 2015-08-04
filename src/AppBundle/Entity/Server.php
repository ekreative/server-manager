<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Server
 *
 * @ORM\Entity
 */
class Server
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
     * @Assert\Ip()
     * @Assert\NotBlank()
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $os;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $autoUpdates;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $ntp;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Site", inversedBy="servers", cascade={"persist"})
     */
    private $sites;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="serverRoot", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $rootLogin;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="serverHosting", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $hostingLogin;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="serverUser", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $userLogin;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="server", cascade={"persist", "remove"})
     */
    private $domains;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->domains = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $ip
     * @return Server
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $os
     * @return Server
     */
    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * @return string
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param boolean $autoUpdates
     * @return Server
     */
    public function setAutoUpdates($autoUpdates)
    {
        $this->autoUpdates = $autoUpdates;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAutoUpdates()
    {
        return $this->autoUpdates;
    }

    /**
     * @return boolean
     */
    public function getNtp()
    {
        return $this->ntp;
    }

    /**
     * @param boolean $ntp
     * @return Server
     */
    public function setNtp($ntp)
    {
        $this->ntp = $ntp;

        return $this;
    }

    /**
     * @param Site $sites
     * @return Server
     */
    public function addSite(Site $sites)
    {
        $this->sites[] = $sites;

        return $this;
    }

    /**
     * @param Site $sites
     */
    public function removeSite(Site $sites)
    {
        $this->sites->removeElement($sites);
    }

    /**
     * @return Collection
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * @param Login $rootLogin
     * @return Server
     */
    public function setRootLogin(Login $rootLogin = null)
    {
        $this->rootLogin = $rootLogin;

        return $this;
    }

    /**
     * @return Login
     */
    public function getRootLogin()
    {
        return $this->rootLogin;
    }

    /**
     * @param Login $hostingLogin
     * @return Server
     */
    public function setHostingLogin(Login $hostingLogin = null)
    {
        $this->hostingLogin = $hostingLogin;

        return $this;
    }

    /**
     * @return Login
     */
    public function getHostingLogin()
    {
        return $this->hostingLogin;
    }

    /**
     * @param Login $userLogin
     * @return Server
     */
    public function setUserLogin(Login $userLogin = null)
    {
        $this->userLogin = $userLogin;

        return $this;
    }

    /**
     * @return Login
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }

    /**
     * @param Domain $domains
     * @return Server
     */
    public function addDomain(Domain $domains)
    {
        $this->domains[] = $domains;

        return $this;
    }

    /**
     * @param Domain $domains
     */
    public function removeDomain(Domain $domains)
    {
        $this->domains->removeElement($domains);
    }

    /**
     * @return Collection
     */
    public function getDomains()
    {
        return $this->domains;
    }
}
