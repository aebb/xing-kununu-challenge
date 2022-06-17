<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\CompanyRepository */
class CompanyRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected CompanyRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(CompanyRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'persist',
                'findOneById',
                'update'
            ])
            ->getMock();

        $prop = new ReflectionProperty(CompanyRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(Company::class);

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
        $expected = $this->createMock(Company::class);

        $this->sut->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($expected);

        $this->assertEquals($expected, $this->sut->findOneById($id));
    }

    /**
     * @covers ::update
     */
    public function testUpdate()
    {
        $model = $this->createMock(Company::class);

        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->update($model));
    }
}
