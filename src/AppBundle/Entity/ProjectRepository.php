<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function getProject(array $redmineProject)
    {
        $project = $this->find($redmineProject['id']);
        if (!$project) {
            $project = new Project($redmineProject['id']);
            $project->setName($redmineProject['name']);
            $this->getEntityManager()->persist($project);
        }
        return $project;
    }
}
