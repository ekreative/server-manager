<?php

namespace AppBundle\Form\ModelTransformer;

use AppBundle\Entity\Project;
use AppBundle\Redmine\Projects;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class ProjectModelTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var Projects
     */
    private $projects;

    public function __construct(ObjectManager $em, Projects $projects)
    {
        $this->em = $em;
        $this->projects = $projects;
    }

    /**
     * @param Project $value
     * @return string
     */
    public function transform($value)
    {
        if ($value) {
            return $value->getName();
        }
        return null;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function reverseTransform($value)
    {
        if ($value) {
            $redmineProject = $this->projects->getProjectByName($value);
            if ($redmineProject) {
                return $this->em->getRepository('AppBundle:Project')->getProject($redmineProject);
            }
        }
        return null;
    }
}
