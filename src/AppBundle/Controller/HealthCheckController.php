<?php

namespace AppBundle\Controller;

use AppBundle\Entity\HealthCheck;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HealthCheckController
{

    /**
     * @Template()
     * @param \AppBundle\Entity\HealthCheck $healthCheck
     *
     * @return array
     */
    public function showAction(HealthCheck $healthCheck)
    {
        return [
            'entity' => $healthCheck
        ];
    }
}
