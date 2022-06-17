<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\RatingRepository */
class RatingRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected RatingRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(RatingRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'persist',
            ])
            ->getMock();

        $prop = new ReflectionProperty(RatingRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(Rating::class);

        $this->entityManager->expects($this->once())->method('persist')->with($model);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->persist($model));
    }
}
