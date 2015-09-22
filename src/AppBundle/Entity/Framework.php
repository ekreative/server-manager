<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\AuthoreditorEntity;

/**
 * Framework
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Framework
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
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * Semvar regex - https://github.com/sindresorhus/semver-regex/blob/master/index.js
     *
     * @ORM\Column(name="currentVersion", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/\bv?(?:0|[1-9][0-9]*)\.(?:0|[1-9][0-9]*)\.(?:0|[1-9][0-9]*)(?:-[\da-z\-]+(?:\.[\da-z\-]+)*)?(?:\+[\da-z\-]+(?:\.[\da-z\-]+)*)?\b/i", message="Enter a valid semvar version")
     */
    private $currentVersion;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Site", mappedBy="framework")
     */
    private $sites;

    use AuthoreditorEntity;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
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
     * @return Framework
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
     * @param string $currentVersion
     * @return Framework
     */
    public function setCurrentVersion($currentVersion)
    {
        $this->currentVersion = $currentVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * @param Site $sites
     * @return Framework
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

    public function __toString()
    {
        return $this->name;
    }
}
