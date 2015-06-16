<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Site
 *
 * @ORM\Entity
 */
class Site
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $live;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $framework;

    /**
     * @var string
     * Semvar regex - https://github.com/sindresorhus/semver-regex/blob/master/index.js
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/\bv?(?:0|[1-9][0-9]*)\.(?:0|[1-9][0-9]*)\.(?:0|[1-9][0-9]*)(?:-[\da-z\-]+(?:\.[\da-z\-]+)*)?(?:\+[\da-z\-]+(?:\.[\da-z\-]+)*)?\b/ig")
     */
    private $frameworkVersion;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="sites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $project;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="siteAdmin", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $adminLogin;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="siteDatabase", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $databaseLogin;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Server", mappedBy="sites", cascade={"persist"})
     */
    private $servers;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="site", cascade={"persist", "remove"})
     */
    private $domains;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
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
     * @param boolean $live
     * @return Site
     */
    public function setLive($live)
    {
        $this->live = $live;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * @param string $framework
     * @return Site
     */
    public function setFramework($framework)
    {
        $this->framework = $framework;

        return $this;
    }

    /**
     * @return string
     */
    public function getFramework()
    {
        return $this->framework;
    }

    /**
     * @param string $frameworkVersion
     * @return Site
     */
    public function setFrameworkVersion($frameworkVersion)
    {
        $this->frameworkVersion = $frameworkVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrameworkVersion()
    {
        return $this->frameworkVersion;
    }

    /**
     * @param string $name
     * @return Site
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
     * @param Project $project
     * @return Site
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Login $adminLogin
     * @return Site
     */
    public function setAdminLogin(Login $adminLogin = null)
    {
        $this->adminLogin = $adminLogin;

        return $this;
    }

    /**
     * @return Login
     */
    public function getAdminLogin()
    {
        return $this->adminLogin;
    }

    /**
     * @param Login $databaseLogin
     * @return Site
     */
    public function setDatabaseLogin(Login $databaseLogin = null)
    {
        $this->databaseLogin = $databaseLogin;

        return $this;
    }

    /**
     * @return Login
     */
    public function getDatabaseLogin()
    {
        return $this->databaseLogin;
    }

    /**
     * @param Server $servers
     * @return Site
     */
    public function addServer(Server $servers)
    {
        $this->servers[] = $servers;

        return $this;
    }

    /**
     * @param Server $servers
     */
    public function removeServer(Server $servers)
    {
        $this->servers->removeElement($servers);
    }

    /**
     * @return Collection
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @param Domain $domains
     * @return Site
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
