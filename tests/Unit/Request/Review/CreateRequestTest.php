<?php

namespace App\Tests\Unit\Request\Review;

use App\Request\Review\CreateRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \App\Request\Review\CreateRequest
 * @covers \App\Request\RequestModel
 */
class CreateRequestTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getTitle
     * @covers ::getPro
     * @covers ::getContra
     * @covers ::getSuggestions
     * @covers ::getCompanyId
     * @covers ::getCulture
     * @covers ::getManagement
     * @covers ::getWorkLifeBalance
     * @covers ::getCareerDevelopment
     * @covers ::getRequest
     * @covers ::getToken
     */
    public function testConstruct()
    {
        $request = $this->createMock(Request::class);
        $body = json_encode([
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
        ]);

        $authorizationToken = 'foo-bar';

        $params = $this->createMock(ParameterBagInterface::class);
        $params
            ->expects($this->once())
            ->method('get')
            ->with('X-AUTH-TOKEN')
            ->willReturn($authorizationToken);
        $request->headers = $params;


        $request->expects($this->once())->method('getContent')->willReturn($body);

        $sut = new CreateRequest($request);

        $body = json_decode($body, true);
        $this->assertEquals($body['title'], $sut->getTitle());
        $this->assertEquals($body['pro'], $sut->getPro());
        $this->assertEquals($body['contra'], $sut->getContra());
        $this->assertEquals($body['suggestions'], $sut->getSuggestions());
        $this->assertEquals($body['company'], $sut->getCompanyId());
        $this->assertEquals($body['rating']['culture'], $sut->getCulture());
        $this->assertEquals($body['rating']['management'], $sut->getManagement());
        $this->assertEquals($body['rating']['workLifeBalance'], $sut->getWorkLifeBalance());
        $this->assertEquals($body['rating']['careerDevelopment'], $sut->getCareerDevelopment());

        $this->assertEquals($authorizationToken, $sut->getToken());
        $this->assertEquals($request, $sut->getRequest());
    }
}
