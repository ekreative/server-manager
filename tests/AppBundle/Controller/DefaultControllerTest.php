<?php

namespace AppBundle\Tests\Command;

use Tests\AppTestCase;

include_once __DIR__.'/../../AppTestCase.php';

class DefaultControllerTest extends AppTestCase
{
    public function testIndex()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
