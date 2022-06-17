<?php

namespace App\Tests\Integration\Endpoints\Company;

use App\Tests\Integration\EndpointTester;
use App\Tests\Integration\Fixtures\TestFixture;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass  \App\Entity\Company
 * @covers \App\Controller\CompanyController
 * @covers \App\Service\CompanyService
 * @covers \App\Repository\CompanyRepository
 * @covers \App\Utils\AbstractController
 * @covers \App\Utils\AbstractService
 */
class ListEndpointTest extends EndpointTester
{
    public function testExecuteNoAuth()
    {
        $this->client->request(
            'GET',
            '/company/best',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'fake-token']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals(['message' => 'Invalid credentials.'], json_decode($response->getContent(), true));
    }

    public function testExecuteBadRequest()
    {
        $fixtures = new TestFixture();
        $fixtures->addUser($this->passwordHasher);
        $this->loadFixture($fixtures);

        $this->client->request(
            'GET',
            '/company/best?start=x&count=x',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $fixtures->getRecords()[0]->getApiToken()]
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $expectedResult = [
            'errorCode' => 0,
            'message' => 'start parameter must be an integer & count parameter must be an integer'
        ];

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals($expectedResult, $responseBody);
    }

    public function testExecuteList()
    {
        $fixtures = $this->loadSamples();
        $user = $fixtures->getRecords()[0];
        $this->loadFixture($fixtures);

        $this->client->request(
            'GET',
            '/company/best',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $user->getApiToken()],
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $expectedResult = [
            [
                'company' => 'company10',
                'score' => 5.0,
            ],
            [
                'company' => 'company9',
                'score' => 5.0,
            ],
            [
                'company' => 'company8',
                'score' => 4.5,
            ],
            [
                'company' => 'company7',
                'score' => 4.0,
            ],
            [
                'company' => 'company6',
                'score' => 3.5,
            ],
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResult, $responseBody);
    }

    public function testExecuteListStartAndCount()
    {
        $fixtures = $this->loadSamples();
        $user = $fixtures->getRecords()[0];
        $this->loadFixture($fixtures);

        $this->client->request(
            'GET',
            '/company/best?start=1&count=2',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $user->getApiToken()],
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $expectedResult = [
            [
                'company' => 'company9',
                'score' => 5.0,
            ],
            [
                'company' => 'company8',
                'score' => 4.5,
            ],
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResult, $responseBody);
    }

    public function testExecuteFilter()
    {
        $fixtures = $this->loadSamples();
        $user = $fixtures->getRecords()[0];
        $this->loadFixture($fixtures);

        $this->client->request(
            'GET',
            '/company/best?search=1',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $user->getApiToken()],
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $expectedResult = [
            [
                'company' => 'company10',
                'score' => 5.0,
            ],
            [
                'company' => 'company1',
                'score' => 1.0,
            ],
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResult, $responseBody);
    }

    private function loadSamples()
    {
        $fixtures = new TestFixture();
        $fixtures->addUser($this->passwordHasher);
        $fixtures->addCompany('company1', 100);
        $fixtures->addCompany('company2', 150);
        $fixtures->addCompany('company3', 200);
        $fixtures->addCompany('company4', 250);
        $fixtures->addCompany('company5', 300);
        $fixtures->addCompany('company6', 350);
        $fixtures->addCompany('company7', 400);
        $fixtures->addCompany('company8', 450);
        $fixtures->addCompany('company9', 500);
        $fixtures->addCompany('company10', 500);

        return $fixtures;
    }
}
