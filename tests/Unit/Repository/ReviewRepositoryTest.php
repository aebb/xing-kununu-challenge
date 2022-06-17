<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\ReviewRepository */
class ReviewRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ReviewRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(ReviewRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'persist',
                'findOneById',
            ])
            ->getMock();

        $prop = new ReflectionProperty(ReviewRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(Review::class);

        $this->entityManager->expects($this->once())->method('persist')->with($model);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->persist($model));
    }

    /**
     * @covers::findOneById
     */
    public function testFindOneById()
    {
        $id = 1;
        $expected = $this->createMock(Review::class);

        $this->sut->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($expected);

        $this->assertEquals($expected, $this->sut->findOneById($id));
    }
}
