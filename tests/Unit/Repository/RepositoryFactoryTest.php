<?php

namespace App\Tests\Unit\Repository;

use App\Repository\CompanyRepository;
use App\Repository\CompanyScoreRepository;
use App\Repository\ReviewRepository;
use App\Repository\SentimentRepository;
use App\Repository\UserRepository;
use App\Repository\RepositoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Repository\RepositoryFactory
*/
class RepositoryFactoryTest extends TestCase
{
    protected ManagerRegistry $registry;
    protected EntityManagerInterface $entityManager;
    protected RepositoryFactory $sut;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(RepositoryFactory::class)
            ->setConstructorArgs([
                $this->registry,
                $this->entityManager,
            ])
            ->onlyMethods(['createRepository'])
            ->getMock();
    }


    /**
     * @covers ::__construct
     * @covers ::createRepository
     * @covers ::getUserRepository
     */
    public function testUserRepository()
    {
        $repository = $this->createMock(UserRepository::class);

        $this->sut
            ->expects($this->once())
            ->method('createRepository')
            ->with(UserRepository::class, $this->registry, $this->entityManager)
            ->willReturn($repository);

        $this->assertSame($repository, $this->sut->getUserRepository());
    }

    /**
     * @covers ::__construct
     * @covers ::createRepository
     * @covers ::getCompanyRepository
     */
    public function testCompanyRepository()
    {
        $repository = $this->createMock(CompanyRepository::class);

        $this->sut
            ->expects($this->once())
            ->method('createRepository')
            ->with(CompanyRepository::class, $this->registry, $this->entityManager)
            ->willReturn($repository);

        $this->assertSame($repository, $this->sut->getCompanyRepository());
    }

    /**
     * @covers ::__construct
     * @covers ::createRepository
     * @covers ::getCompanyScoreRepository
     */
    public function testCompanyScoreRepository()
    {
        $repository = $this->createMock(CompanyScoreRepository::class);

        $this->sut
            ->expects($this->once())
            ->method('createRepository')
            ->with(CompanyScoreRepository::class, $this->registry, $this->entityManager)
            ->willReturn($repository);

        $this->assertSame($repository, $this->sut->getCompanyScoreRepository());
    }

    /**
     * @covers ::__construct
     * @covers ::createRepository
     * @covers ::getReviewRepository
     */
    public function testReviewRepository()
    {
        $repository = $this->createMock(ReviewRepository::class);

        $this->sut
            ->expects($this->once())
            ->method('createRepository')
            ->with(ReviewRepository::class, $this->registry, $this->entityManager)
            ->willReturn($repository);

        $this->assertSame($repository, $this->sut->getReviewRepository());
    }

    /**
     * @covers ::__construct
     * @covers ::createRepository
     * @covers ::getSentimentRepository
     */
    public function testSentimentRepository()
    {
        $repository = $this->createMock(SentimentRepository::class);

        $this->sut
            ->expects($this->once())
            ->method('createRepository')
            ->with(SentimentRepository::class, $this->registry, $this->entityManager)
            ->willReturn($repository);

        $this->assertSame($repository, $this->sut->getSentimentRepository());
    }
}
