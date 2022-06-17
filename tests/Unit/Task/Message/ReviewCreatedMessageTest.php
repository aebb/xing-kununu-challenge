<?php

namespace App\Tests\Unit\Task\Message;

use App\Task\Message\ReviewCreatedMessage;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass  \App\Task\Message\ReviewCreatedMessage */
class ReviewCreatedMessageTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getReviewId
     */
    public function testMessage()
    {
        $value = 3;

        $sut = new ReviewCreatedMessage($value);
        $this->assertEquals($value, $sut->getReviewId());
    }
}
