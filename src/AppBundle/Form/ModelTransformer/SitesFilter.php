<?php

namespace AppBundle\Form\ModelTransformer;

class SitesFilter
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $framework;
    /**
     * @var string
     */
    private $status;

    /**
     * @var array
     */
    private $projects = [];
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
     * @return string
     */
    public function getFramework()
    {
        return $this->framework;
    }

    /**
     * @param string $framework
     */
    public function setFramework($framework)
    {
        $this->framework = $framework;
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

    /**
     * @return array
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param int $project
     */
    public function addProject($project)
    {
        $this->projects[] = $project;
    }
}
