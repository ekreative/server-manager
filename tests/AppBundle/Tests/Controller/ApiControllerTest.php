<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AppTestCase;

class ApiControllerTest extends AppTestCase
{
    public function testServers()
    {
        $this->client->request('POST', '/login',[],[],[],json_encode(['login'=>['username'=>'testtest','password'=>'testtest']]));
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $user = json_decode($response->getContent(), true);
        $this->client->request('GET', '/api/servers', [], [], ['HTTP_X-API-Key'=>$user['user']['apiKey']]);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $servers = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $servers);
    }
}
