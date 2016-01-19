<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AppTestCase;

class DefaultControllerTest extends AppTestCase
{
    public function testIndex()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
