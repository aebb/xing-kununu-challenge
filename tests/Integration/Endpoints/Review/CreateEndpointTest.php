<?php

namespace App\Tests\Integration\Endpoints\Review;

use App\Tests\Integration\EndpointTester;
use App\Tests\Integration\Fixtures\TestFixture;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass  \App\Entity\Review
 * @covers \App\Controller\ReviewController
 * @covers \App\Service\ReviewService
 * @covers \App\Repository\ReviewRepository
 * @covers \App\Utils\AbstractController
 * @covers \App\Utils\AbstractService
 */
class CreateEndpointTest extends EndpointTester
{
    public function testExecutePostNoAuth()
    {
        $this->client->request(
            'POST',
            '/review',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'fake-token']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals(['message' => 'Invalid credentials.'], json_decode($response->getContent(), true));

        $this->assertCount(0, $this->transport->getSent());
    }

    public function testExecutePostBadRequest()
    {
        $fixtures = new TestFixture();
        $fixtures->addUser($this->passwordHasher);
        $this->loadFixture($fixtures);

        $body = [
            'title' => 'title',
            'company' => 1,
            'rating' => [
                'culture' => 1,
                'management' => 1,
                'workLifeBalance' => 1,
                'careerDevelopment' => 1,
            ]
        ];

        $this->client->request(
            'POST',
            '/review',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $fixtures->getRecords()[0]->getApiToken()],
            json_encode($body)
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $expectedResult = [
            'errorCode' => 0,
            'message' => 'Your title must be at least 10 characters long'
        ];

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals($expectedResult, $responseBody);

        $this->assertCount(0, $this->transport->getSent());
    }

    public function testExecutePostCompanyNotFound()
    {
        $fixtures = new TestFixture();
        $fixtures->addUser($this->passwordHasher);
        $this->loadFixture($fixtures);

        $body = [
            'title' => 'title with at least 10 chars',
            'pro' => 'pro',
            'contra' => 'contra',
            'suggestions' => 'suggestions',
            'company' => 1,
            'rating' => [
                'culture' => 1,
                'management' => 1,
                'workLifeBalance' => 1,
                'careerDevelopment' => 1,
            ]
        ];

        $this->client->request(
            'POST',
            '/review',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $fixtures->getRecords()[0]->getApiToken()],
            json_encode($body)
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $expectedResult = [
            'errorCode' => 1,
            'message' => 'Company not found'
        ];

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals($expectedResult, $responseBody);

        $this->assertCount(0, $this->transport->getSent());
    }

    public function testExecutePostSuccess()
    {
        $name = 'johnny company';
        $fixtures = new TestFixture();
        $fixtures->addUser($this->passwordHasher);
        $fixtures->addCompany($name, 100);
        $this->loadFixture($fixtures);

        $body = [
            'title' => 'title with at least 10 chars',
            'company' => 1,
            'rating' => [
                'culture' => 1,
                'management' => 1,
                'workLifeBalance' => 1,
                'careerDevelopment' => 1,
            ]
        ];

        $this->client->request(
            'POST',
            '/review',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $fixtures->getRecords()[0]->getApiToken()],
            json_encode($body)
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertCount(4, $responseBody);
        $this->assertEquals($body['title'], $responseBody['title']);
        $this->assertEquals($name, $responseBody['company']);
        $this->assertEquals('admin', $responseBody['user']);
        $this->assertEquals($body['rating'], $responseBody['rating']);

        $this->assertCount(1, $this->transport->getSent());
    }

    public function testExecutePostSuccessFullDescription()
    {
        $name = 'johnny company';
        $fixtures = new TestFixture();
        $fixtures->addUser($this->passwordHasher);
        $fixtures->addCompany($name, 100);
        $this->loadFixture($fixtures);

        $body = [
            'title' => 'title with at least 10 chars',
            'pro' => 'pro',
            'contra' => 'pro',
            'suggestions' => 'pro',
            'company' => 1,
            'rating' => [
                'culture' => 1,
                'management' => 1,
                'workLifeBalance' => 1,
                'careerDevelopment' => 1,
            ]
        ];

        $this->client->request(
            'POST',
            '/review',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $fixtures->getRecords()[0]->getApiToken()],
            json_encode($body)
        );

        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertCount(7, $responseBody);
        $this->assertEquals($body['title'], $responseBody['title']);
        $this->assertEquals($body['pro'], $responseBody['pro']);
        $this->assertEquals($body['contra'], $responseBody['contra']);
        $this->assertEquals($body['suggestions'], $responseBody['suggestions']);
        $this->assertEquals($name, $responseBody['company']);
        $this->assertEquals('admin', $responseBody['user']);
        $this->assertEquals($body['rating'], $responseBody['rating']);

        $this->assertCount(1, $this->transport->getSent());
    }
}
