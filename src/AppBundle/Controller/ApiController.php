<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Server;
use AppBundle\Entity\Site;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends Controller
{

    /**
     * @Route("/api/servers", name="api_servers")
     */
    public function serversAction()
    {
        $doctrine = $this->getDoctrine();
        $projects = $doctrine->getRepository('AppBundle:Project')->findById(array_map(function ($project) {
            return $project['id'];
        }, $this->get('projects')->getAllProjects()));
        /** @var Project $project */
        $sitesResult = [];
        foreach ($projects as $project) {
            /** @var Site $site */
            foreach ($project->getSites() as $site) {
                $siteResult = (object)['id'=>$site->getId(), 'slug'=>$site->getName(), 'name'=>$site->getName(), 'servers'=> null];
                $siteResult->servers = [];
                /** @var Server $server */
                foreach ($site->getServers() as $server) {
                    $siteResult->servers[] = (object)['name'=>'user login','user'=> $server->getUserLogin()->getUsername(),'host'=>$server->getIp()];
                    $siteResult->servers[] = (object)['name'=>'root login','user'=> $server->getRootLogin()->getUsername(),'host'=>$server->getIp()];
                }
                $sitesResult[] = $siteResult;
            }
        }
        return JsonResponse::create($sitesResult);
    }
}
