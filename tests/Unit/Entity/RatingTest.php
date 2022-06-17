<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Rating;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Entity\Rating */
class RatingTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getScore
     * @covers ::jsonSerialize
     */
    public function testRating()
    {
        $value = 3;

        $sut = new Rating($value, $value, $value, $value);
        $this->assertEquals(12, $sut->getScore());
        $this->assertEquals(
            [
            'culture' => 3,
            'management' => 3,
            'workLifeBalance' => 3,
            'careerDevelopment' => 3,
            ],
            $sut->jsonSerialize()
        );
    }
}
