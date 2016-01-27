<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Domain;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DomainController extends Controller
{
    /**
     * @Template()
     */
    public function showAction(Domain $domain)
    {
        return [
            'entity' => $domain
        ];
    }
}
