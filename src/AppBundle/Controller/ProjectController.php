<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use GuzzleHttp\Promise;

class ProjectController extends Controller
{
    /**
     * @Route("/project/typeahead", name="project_typeahead")
     */
    public function typeaheadAction(Request $request)
    {
        $q = $request->query->get('q', '');
        return new JsonResponse(array_map(function($project) {
            return $project['name'];
        }, array_filter($this->get('projects')->getAllProjects(), function($project) use ($q) {
            return empty($q) || stripos($project['name'], $q) !== false;
        })));
    }
}
