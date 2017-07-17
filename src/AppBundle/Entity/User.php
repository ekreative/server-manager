<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
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

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Add sitesDevelopedBy
     *
     * @param \AppBundle\Entity\Site $sitesDevelopedBy
     *
     * @return User
     */
    public function addSitesDevelopedBy(\AppBundle\Entity\Site $sitesDevelopedBy)
    {
        $this->sitesDevelopedBy[] = $sitesDevelopedBy;

        return $this;
    }

    /**
     * Remove sitesDevelopedBy
     *
     * @param \AppBundle\Entity\Site $sitesDevelopedBy
     */
    public function removeSitesDevelopedBy(\AppBundle\Entity\Site $sitesDevelopedBy)
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
     * @param \AppBundle\Entity\Site $sitesManagedBy
     *
     * @return User
     */
    public function addSitesManagedBy(\AppBundle\Entity\Site $sitesManagedBy)
    {
        $this->sitesManagedBy[] = $sitesManagedBy;

        return $this;
    }

    /**
     * Remove sitesManagedBy
     *
     * @param \AppBundle\Entity\Site $sitesManagedBy
     */
    public function removeSitesManagedBy(\AppBundle\Entity\Site $sitesManagedBy)
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
