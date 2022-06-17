<?php

namespace App\Tests\Unit\Entity;

use App\Entity\CompanyScore;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Entity\CompanyScore */
class CompanyScoreTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::updateScore
     */
    public function testCompanyScore()
    {
        $score = 123;

        $sut = new CompanyScore();
        $prop = new ReflectionProperty(CompanyScore::class, 'score');
        $prop->setAccessible(true);
        $this->assertEquals(0, $prop->getValue($sut));
        $sut->updateScore($score);
        $this->assertEquals($score, $prop->getValue($sut));
    }
}
