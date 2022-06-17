<?php

namespace App\Tests\Unit\Task;

use App\Entity\Review;
use App\Service\ReviewAnalyticsService;
use App\Task\Message\ReviewCreatedMessage;
use App\Task\CompanyScoreHandler;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionProperty;

/** @coversDefaultClass  \App\Task\CompanyScoreHandler */
class CompanyScoreHandlerTest extends TestCase
{
    private ReviewAnalyticsService $service;

    private LoggerInterface $logger;

    private CompanyScoreHandler $sut;


    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->createMock(ReviewAnalyticsService::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new CompanyScoreHandler($this->service, $this->logger);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $this->sut = new CompanyScoreHandler($this->service, $this->logger);
        $prop = new ReflectionProperty(CompanyScoreHandler::class, 'service');
        $prop->setAccessible(true);
        $this->assertEquals($this->service, $prop->getValue($this->sut));

        $prop = new ReflectionProperty(CompanyScoreHandler::class, 'logger');
        $prop->setAccessible(true);
        $this->assertEquals($this->logger, $prop->getValue($this->sut));
    }

    public function testInvokeSuccess()
    {
        $message = new ReviewCreatedMessage(123);
        $this->service->expects($this->once())
            ->method('calculateScore');

        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->sut->__invoke($message);
    }

    public function testInvokeFail()
    {
        $message = new ReviewCreatedMessage(123);
        $this->service->expects($this->once())
            ->method('calculateScore')
            ->willThrowException(new Exception('error'));

        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->logger->expects($this->once())
            ->method('error');

        $this->sut->__invoke($message);
    }
}
