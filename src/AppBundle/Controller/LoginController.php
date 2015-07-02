<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Login;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{
    /**
     * @Template()
     */
    public function showAction(Login $login)
    {
        return [
            'entity' => $login
        ];
    }
}
