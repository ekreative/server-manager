<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/typeahead", name="project_typeahead")
     */
    public function typeaheadAction(Request $request)
    {
        $q = $request->query->get('q', '');

        $cache = new ApcuAdapter('ekreative', 3600);

        if ($cache->getItem('projects')->get() != null) {
            $projects = $cache->getItem('projects')->get();
        } else {
            $projects = $this->get('projects')->getAllProjects();
            $cache->save($cache->getItem('projects')->set($projects));
        }
        $response = array_values(array_map(function ($project) {
            return $project;
        }, array_filter($projects, function ($project) use ($q) {
            return (empty($q) || stripos($project['name'], strtolower($q)) !== false);
        })));

        return $this->json($response);
    }

    /**
     * @Route("/project/members", name="project_members")
     * Method("GET")
     */
    public function membersAction(Request $request)
    {
        if ($request->query->get("project")) {
            $project = $this->getDoctrine()->getRepository(Project::class)->find($request->query->get("project"));
            if ($project && $project->getClient()) {
                $client = $this->getDoctrine()->getRepository(Client::class)->findOneBy(
                    ['id' => $project->getClient()->getId()]
                );
            }

            $redmineClientService = $this->container->get('redmine_client');
            $uri = '/projects/' . $request->query->get("project") . '/memberships.json';

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

            return $this->json([
                'client' => $client ?? null,
                'developers' => $developers,
                'managers' => $managers,
            ]);
        }
        return $this->json(['error' => "Query parameter project is empty"], 406);
    }
}
