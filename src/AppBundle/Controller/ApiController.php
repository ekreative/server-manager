<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Server;
use AppBundle\Entity\Site;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends Controller
{

    /**
     * Get a list of sites
     *
     * @Route("/api/sites", name="api_sites")
     * @ApiDoc(output={"class"="AppBundle\Entity\Site", "name"="sites"})
     * @Method({"GET"})
     */
    public function sitesAction()
    {
        $doctrine = $this->getDoctrine();

        $projects = $doctrine->getRepository('AppBundle:Project')->findBy(['id' => array_map(function ($project) {
            return $project['id'];
        }, $this->get('projects')->getAllProjects())]);

        $sitesArrays = array_map(function(Project $project) {
            return $project->getSites()->toArray();
        }, $projects);

        $sites = call_user_func_array('array_merge', $sitesArrays);

        return new JsonResponse([
            'sites' =>  $sites
        ]);
    }
}
