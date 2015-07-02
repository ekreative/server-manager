<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Server;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServerController extends Controller
{
    /**
     * @Template()
     */
    public function showAction(Server $server)
    {
        return [
            'entity' => $server
        ];
    }
}
