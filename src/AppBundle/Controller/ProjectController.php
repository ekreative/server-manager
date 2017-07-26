<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/typeahead", name="project_typeahead")
     * @Cache(expires="tomorrow")
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

        $response = new JsonResponse(array_values(array_map(function ($project) {
            return $project;
        }, array_filter($this->get('projects')->getAllProjects(), function ($project) use ($q, $siteNames) {
            return (empty($q) || stripos($project['name'], $q) !== false);
        }))));

        return $response;
    }

    /**
     * @Route("/project/{project}/members", name="project_members")
     * Method("GET")
     */
    public function membersAction(Project $project)
    {
        if ($project && $project->getClient()) {
            $client = $this->getDoctrine()->getRepository(Client::class)->findOneBy(
                ['id' => $project->getClient()->getId()]
            );
        }

        $redmineClientService = $this->container->get('redmine_client');
        $uri = '/projects/' . $project->getId() . '/memberships.json';

        $result = \GuzzleHttp\json_decode($redmineClientService->get($uri)->getBody(), true);
        $developers = [];
        $managers = [];
        foreach ($result['memberships'] as $membership) {
            if ($membership['roles'][0]['name'] == "Developer") {
                $developers[] = $membership['user'];
            }
            if ($membership['roles'][0]['name'] == "Manager") {
                $managers[] = $membership['user'];
            }
        }

        return new JsonResponse([
            'client' => $client ?? null,
            'developers' => $developers,
            'managers' => $managers,
        ]);
    }
}
