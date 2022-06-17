<?php

namespace App\Tests\Unit\Service;

use App\Entity\Company;
use App\Entity\CompanyScore;
use App\Entity\Rating;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\CompanyScoreRepository;
use App\Repository\RepositoryFactory;
use App\Repository\ReviewRepository;
use App\Repository\SentimentRepository;
use App\Service\ReviewAnalyticsService;
use App\Task\Message\ReviewCreatedMessage;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionProperty;

/** @coversDefaultClass  \App\Service\ReviewAnalyticsService */
class ReviewAnalyticsServiceTest extends TestCase
{
    private RepositoryFactory $repositoryFactory;

    private ReviewRepository $reviewRepository;

    private LoggerInterface $logger;

    private ReviewAnalyticsService $sut;


    public function setUp(): void
    {
        parent::setUp();
        $this->repositoryFactory = $this->createMock(RepositoryFactory::class);
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->repositoryFactory
            ->method('getReviewRepository')
            ->willReturn($this->reviewRepository);

        $this->sut = new ReviewAnalyticsService($this->logger, $this->repositoryFactory);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $this->sut = new ReviewAnalyticsService($this->logger, $this->repositoryFactory);
        $prop = new ReflectionProperty(ReviewAnalyticsService::class, 'repositoryFactory');
        $prop->setAccessible(true);
        $this->assertEquals($this->repositoryFactory, $prop->getValue($this->sut));

        $prop = new ReflectionProperty(ReviewAnalyticsService::class, 'logger');
        $prop->setAccessible(true);
        $this->assertEquals($this->logger, $prop->getValue($this->sut));
    }

    /**
     * @covers ::calculateSentiment
     * @covers ::findReview
     */
    public function testCalculateSentiment()
    {
        $id = 1;
        $message = new ReviewCreatedMessage($id);
        $review = new Review(
            'title ',
            'pro',
            'contra',
            'suggestions',
            $this->createMock(Rating::class),
            $this->createMock(Company::class),
            $this->createMock(User::class),
        );

        $sentimentRepository = $this->createMock(SentimentRepository::class);

        $this->reviewRepository->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($review);

        $this->repositoryFactory->expects($this->once())
            ->method('getSentimentRepository')
            ->willReturn($sentimentRepository);

        $sentimentRepository->expects($this->once())
            ->method('update')
            ->with($review->getSentiment())
            ->willReturn($review->getSentiment());

        $this->assertNotNull($this->sut->calculateSentiment($message));
    }

    /**
     * @covers ::calculateScore
     * @covers ::findReview
     */
    public function testCalculateScore()
    {
        $id = 1;
        $message = new ReviewCreatedMessage($id);


        $rating = new Rating($id, $id, $id, $id);
        $company = $this->createMock(Company::class);
        $score = $this->createMock(CompanyScore::class);
        $user = $this->createMock(User::class);

        $review = new Review(
            'title ',
            'pro',
            'contra',
            'suggestions',
            $rating,
            $company,
            $user
        );
        $this->reviewRepository->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($review);

        $company
            ->method('getReviews')
            ->willReturn(new ArrayCollection([$review]));

        $company
            ->method('getScore')
            ->willReturn($score);

        $score->expects($this->once())
            ->method('updateScore');

        $scoreRepository = $this->createMock(CompanyScoreRepository::class);

        $this->repositoryFactory->expects($this->once())
            ->method('getCompanyScoreRepository')
            ->willReturn($scoreRepository);

        $scoreRepository->expects($this->once())
            ->method('update')
            ->with($company->getScore())
            ->willReturn($company->getScore());

        $this->assertNotNull($this->sut->calculateScore($message));
    }
}
