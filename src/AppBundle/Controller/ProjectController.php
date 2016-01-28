<?php

namespace AppBundle\Controller;

use GuzzleHttp\Promise;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/typeahead", name="project_typeahead")
     */
    public function typeaheadAction(Request $request)
    {
        $siteNames = [];
        $sites = $this->getDoctrine()->getRepository('AppBundle:Site')->findAll();
        foreach ($sites as $site) {
            if ($site->getProject()) {
                $siteNames[] = $site->getProject()->getName();
            }
        }

        $q = $request->query->get('q', '');

        return new JsonResponse(array_values(array_map(function ($project) {
            return $project['name'];
        }, array_filter($this->get('projects')->getAllProjects(), function ($project) use ($q, $siteNames) {
            return (empty($q) || stripos($project['name'], $q) !== false) && !in_array($project['name'], $siteNames) ;
        }))));
    }
}
