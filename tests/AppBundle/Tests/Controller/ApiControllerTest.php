<?php

namespace AppBundle\Tests\Controller;

use Ekreative\RedmineLoginBundle\Client\ClientProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testSites()
    {
        // Only by creating the client twice do we insure the cache exists and that replacing services works
        static::createClient();
        $client = static::createClient();

        $redmineClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $redmineClient->expects($this->exactly(2))->method('__call')->willReturnOnConsecutiveCalls(
            new Response(200, [], json_encode(['user' => [
                'id' => 1,
                'login' => 'test',
                'firstname' => 'firstname',
                'lastname' => 'lastname',
                'mail' => 'test@test.com',
                'api_key' => 'test-api-key',
                'created_on' => date('c'),
                'last_login_on' => date('c')
            ]])),
            new Response(200, [], json_encode(['groups' => []]))
        );
        $client->getContainer()->set('ekreative_redmine_login.redmine', $redmineClient);
        $redmineUserClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $redmineUserClient->expects($this->once())->method('__call')->willReturnOnConsecutiveCalls(
            new Response(200, [], json_encode([
                'projects' => [
                    [
                        'id' => 1,
                        'name' => 'Project 1'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Project 2'
                    ]
                ],
                'total_count' => 2
            ]))
        );
        $clientProvider = $this->getMockBuilder(ClientProvider::class)->disableOriginalConstructor()->getMock();
        $clientProvider->method('get')->willReturn($redmineUserClient);
        $client->getContainer()->set('ekreative_redmine_login.client_provider', $clientProvider);

        $client->getContainer()->get('fixture_loader')->load();

        $client->request('GET', '/api/sites', [], [], ['HTTP_X-API-Key' => 'test-api-key']);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $sites = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $sites);
        $this->assertArrayHasKey('sites', $sites);

        $this->assertInternalType('array', $sites['sites']);
        $this->assertCount(5, $sites['sites']);
    }
}
