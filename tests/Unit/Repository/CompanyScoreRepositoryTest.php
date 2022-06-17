<?php

namespace App\Tests\Unit\Repository;

use App\Entity\CompanyScore;
use App\Repository\CompanyScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\CompanyScoreRepository */
class CompanyScoreRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected CompanyScoreRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(CompanyScoreRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'persist',
                'update'
            ])
            ->getMock();

        $prop = new ReflectionProperty(CompanyScoreRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(CompanyScore::class);

        $this->entityManager->expects($this->once())->method('persist')->with($model);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->persist($model));
    }

    /**
     * @covers ::update
     */
    public function testUpdate()
    {
        $model = $this->createMock(CompanyScore::class);

        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->update($model));
    }
}
