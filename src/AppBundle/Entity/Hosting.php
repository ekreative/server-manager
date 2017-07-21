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
 * Hosting
 *
 * @ORM\Entity
 */
class Hosting implements AuthorEditorable
{
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Server", mappedBy="hosting")
     */
    private $servers;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Add server
     *
     * @param \AppBundle\Entity\Server $server
     *
     * @return Hosting
     */
    public function addServer(\AppBundle\Entity\Server $server)
    {
        $this->servers[] = $server;

        return $this;
    }

    /**
     * Remove server
     *
     * @param \AppBundle\Entity\Server $server
     */
    public function removeServer(\AppBundle\Entity\Server $server)
    {
        $this->servers->removeElement($server);
    }

    /**
     * Get servers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServers()
    {
        return $this->servers;
    }
}
