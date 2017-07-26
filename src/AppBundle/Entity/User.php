<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ekreative\RedmineLoginBundle\Security\RedmineUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends RedmineUser
{
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Site", mappedBy="developer")
     */
    private $sitesDevelopedBy;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Site", mappedBy="responsibleManager")
     */
    private $sitesManagedBy;

    public function __toString()
    {
        return $this->username;
    }

    public function __construct(array $data = null, $isAdmin = null)
    {
        parent::__construct($data, $isAdmin);
        $this->sitesDevelopedBy = new ArrayCollection();
        $this->sitesManagedBy = new ArrayCollection();
    }

    /**
     * Add sitesDevelopedBy
     *
     * @param Site $sitesDevelopedBy
     *
     * @return User
     */
    public function addSitesDevelopedBy(Site $sitesDevelopedBy)
    {
        $this->sitesDevelopedBy[] = $sitesDevelopedBy;

        return $this;
    }

    /**
     * Remove sitesDevelopedBy
     *
     * @param Site $sitesDevelopedBy
     */
    public function removeSitesDevelopedBy(Site $sitesDevelopedBy)
    {
        $this->sitesDevelopedBy->removeElement($sitesDevelopedBy);
    }

    /**
     * Get sitesDevelopedBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSitesDevelopedBy()
    {
        return $this->sitesDevelopedBy;
    }

    /**
     * Add sitesManagedBy
     *
     * @param Site $sitesManagedBy
     *
     * @return User
     */
    public function addSitesManagedBy(Site $sitesManagedBy)
    {
        $this->sitesManagedBy[] = $sitesManagedBy;

        return $this;
    }

    /**
     * Remove sitesManagedBy
     *
     * @param Site $sitesManagedBy
     */
    public function removeSitesManagedBy(Site $sitesManagedBy)
    {
        $this->sitesManagedBy->removeElement($sitesManagedBy);
    }

    /**
     * Get sitesManagedBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSitesManagedBy()
    {
        return $this->sitesManagedBy;
    }
}
