<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Sentiment;
use App\Repository\SentimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\SentimentRepository */
class SentimentRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected SentimentRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(SentimentRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'persist',
                'update'
            ])
            ->getMock();

        $prop = new ReflectionProperty(SentimentRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(Sentiment::class);

        $this->entityManager->expects($this->once())->method('persist')->with($model);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->persist($model));
    }

    /**
     * @covers ::update
     */
    public function testUpdate()
    {
        $model = $this->createMock(Sentiment::class);

        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->update($model));
    }
}
