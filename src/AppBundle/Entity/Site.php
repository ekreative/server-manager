<?php

namespace AppBundle\Entity;

use AppBundle\AuthorEditor\AuthorEditorable;
use AppBundle\AuthorEditor\AuthorEditorableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Site
 *
 * @ORM\Entity(repositoryClass="SiteRepository")
 */
class Site implements AuthorEditorable, \JsonSerializable
{
    const STATUS_SUPPORTED = 'supported';
    const STATUS_UNSUPPORTED = 'unsupported';
    const SLA_STANDARD = 'standard';
    const SLA_ADVANCED = 'advanced';

    use AuthorEditorableEntity;
    use TimestampableEntity;

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
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $sla;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @var string
     * Semvar regex - https://github.com/sindresorhus/semver-regex/blob/master/index.js
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/^(?:0|[1-9][0-9]*)\.(?:0|[1-9][0-9]*)?(\.(?:0|[1-9][0-9]*)(?:-[\da-z\-]+(?:\.[\da-z\-]+)*)?(?:\+[\da-z\-]+(?:\.[\da-z\-]+)*))?\b/i", message="Enter a valid semvar version")
     */
    private $frameworkVersion;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="sites", fetch="EAGER")
     * @Assert\NotBlank(message="Enter a current name of Redmine project")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $project;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="siteAdmin", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $adminLogin;

    /**
     * @var Login
     *
     * @ORM\OneToOne(targetEntity="Login", mappedBy="siteDatabase", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $databaseLogin;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Server", mappedBy="sites", cascade={"persist"})
     * @Assert\Valid()
     */
    private $servers;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="site", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $domains;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="HealthCheck", mappedBy="site", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $healthChecks;

    /**
     * @var Framework
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Framework", inversedBy="sites")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $framework;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sitesDevelopedBy")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    private $developer;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sitesManagedBy")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    private $responsibleManager;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $siteCompletedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $slaEndAt;

    /**
     *
     * '0' => sites what are placed on our hosting
     * '1' => sites what are placed on external hosting
     * @var string
     * @ORM\Column(type="string", length=60)
     *
     **/
    private $status;

    public function __construct()
    {
        $this->sla = $this::SLA_STANDARD;
        $this->servers = new ArrayCollection();
        $this->domains = new ArrayCollection();
        $this->healthChecks = new ArrayCollection();
        $this->setAdminLogin(new Login(Login::TYPE_SITE));
        $this->setDatabaseLogin(new Login(Login::TYPE_DB));
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
        if ($this->project) {
            $this->project->removeSite($this);
        }

        $this->project = $project;

        if ($project) {
            $project->addSite($this);
        }

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
        $servers->addSite($this);

        return $this;
    }

    /**
     * @param Server $servers
     */
    public function removeServer(Server $servers)
    {
        $this->servers->removeElement($servers);
        $servers->removeSite($this);
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
        $domains->setSite($this);

        return $this;
    }

    /**
     * @param Domain $domains
     */
    public function removeDomain(Domain $domains)
    {
        $this->domains->removeElement($domains);
        $domains->setSite(null);
    }

    /**
     * @return Collection
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @param HealthCheck $healthCheck
     *
     * @return $this
     */
    public function addHealthCheck(HealthCheck $healthCheck)
    {
        $this->healthChecks[] = $healthCheck;
        $healthCheck->setSite($this);

        return $this;
    }

    /**
     * @param HealthCheck $healthCheck
     */
    public function removeHealthCheck(HealthCheck $healthCheck)
    {
        $this->healthChecks->removeElement($healthCheck);
    }

    /**
     * @return Collection
     */
    public function getHealthChecks()
    {
        return $this->healthChecks;
    }

    /**
     * @param Framework $framework
     * @return Site
     */
    public function setFramework(Framework $framework = null)
    {
        $this->framework = $framework;

        return $this;
    }

    /**
     * @return Framework
     */
    public function getFramework()
    {
        return $this->framework;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'servers' => $this->getServers()->toArray()
        ];
    }


    /**
     * Set sla
     *
     * @param boolean $sla
     *
     * @return Site
     */
    public function setSla($sla)
    {
        $this->sla = $sla;

        return $this;
    }

    /**
     * Get sla
     *
     * @return boolean
     */
    public function getSla()
    {
        return $this->sla;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Site
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }


    /**
     * Set developer
     *
     * @param integer $developer
     *
     * @return Site
     */
    public function setDeveloper($developer)
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * Get developer
     *
     * @return integer
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * Set responsibleManager
     *
     * @param integer $responsibleManager
     *
     * @return Site
     */
    public function setResponsibleManager($responsibleManager)
    {
        $this->responsibleManager = $responsibleManager;

        return $this;
    }

    /**
     * Get responsibleManager
     *
     * @return integer
     */
    public function getResponsibleManager()
    {
        return $this->responsibleManager;
    }

    /**
     * Set siteCompletedAt
     *
     * @param \DateTime $siteCompletedAt
     *
     * @return Site
     */
    public function setSiteCompletedAt($siteCompletedAt)
    {
        $this->siteCompletedAt = $siteCompletedAt;

        return $this;
    }

    /**
     * Get siteCompletedAt
     *
     * @return \DateTime
     */
    public function getSiteCompletedAt()
    {
        return $this->siteCompletedAt;
    }

    /**
     * Set slaEndAt
     *
     * @param \DateTime $slaEndAt
     *
     * @return Site
     */
    public function setSlaEndAt($slaEndAt)
    {
        $this->slaEndAt = $slaEndAt;

        return $this;
    }

    /**
     * Get slaEndAt
     *
     * @return \DateTime
     */
    public function getSlaEndAt()
    {
        return $this->slaEndAt;
    }
}
