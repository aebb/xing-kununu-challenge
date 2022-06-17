<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Entity\Company */
class CompanyTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getScore
     * @covers ::getReviews
     * @covers ::__toString()
     */
    public function testCompany()
    {
        $value = 'foo-bar';
        $sut = new Company($value, $value, $value, $value, $value);

        $this->assertEquals($value, $sut->__toString());
        $this->assertNotNull($sut->getScore());
        $this->assertNotNull($sut->getReviews());
    }
}
