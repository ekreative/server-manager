<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class HealthCheck
{
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
     * @var \AppBundle\Entity\Site
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Site", inversedBy="healthChecks")
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_sync_at", type="datetime", nullable=true)
     */
    private $lastSyncAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     *
     * @return $this
     */
    public function setSite(Site $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastSyncAt()
    {
        return $this->lastSyncAt;
    }

    /**
     * @param \DateTime $lastSyncAt
     *
     * @return $this
     */
    public function setLastSyncAt(\DateTime $lastSyncAt)
    {
        $this->lastSyncAt = $lastSyncAt;

        return $this;
    }

}
