<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Sentiment;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Entity\Sentiment */
class SentimentTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::updateSentiment
     */
    public function testSentiment()
    {
        $positive = 1.0;
        $negative = 2.0;
        $abusive  = 3.0;

        $sut = new Sentiment();
        $sut->updateSentiment($positive, $negative, $abusive);

        $prop = new ReflectionProperty(Sentiment::class, 'positive');
        $prop->setAccessible(true);
        $this->assertEquals($positive, $prop->getValue($sut));

        $prop = new ReflectionProperty(Sentiment::class, 'negative');
        $prop->setAccessible(true);
        $this->assertEquals($negative, $prop->getValue($sut));

        $prop = new ReflectionProperty(Sentiment::class, 'abusive');
        $prop->setAccessible(true);
        $this->assertEquals($abusive, $prop->getValue($sut));

        $prop = new ReflectionProperty(Sentiment::class, 'analyzed');
        $prop->setAccessible(true);
        $this->assertTrue($prop->getValue($sut));

        $prop = new ReflectionProperty(Sentiment::class, 'updatedAt');
        $prop->setAccessible(true);
        $this->assertInstanceOf(\DateTime::class, $prop->getValue($sut));
    }
}
