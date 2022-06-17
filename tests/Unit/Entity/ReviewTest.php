<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
use App\Entity\Rating;
use App\Entity\Review;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Entity\Review */
class ReviewTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getCompany
     * @covers ::getRating
     * @covers ::getSentiment
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testReview()
    {

        $id = 1;
        $name = 'company';
        $email = 'john@mail.com';
        $title = 'title';
        $pro = 'pro';
        $contra = 'contra';
        $suggestions = 'suggestions';
        $rating = new Rating(1, 2, 3, 4);
        $user = new User();
        $user->setEmail($email);
        $company = new Company($name, $name, $name, $name, $name);


        $sut = new Review($title, $pro, $contra, $suggestions, $rating, $company, $user);

        $prop = new ReflectionProperty(Review::class, 'id');
        $prop->setAccessible(true);
        $prop->setValue($sut, 1);

        $this->assertEquals($id, $sut->getId());
        $this->assertEquals($company, $sut->getCompany());
        $this->assertEquals($rating, $sut->getRating());
        $this->assertNotNull($sut->getSentiment());

        $this->assertEquals('title pro contra suggestions', $sut->__toString());
        $this->assertEquals(
            [
                'title' => 'title',
                'rating' => $rating->jsonSerialize(),
                'company' => 'company',
                'user' => 'john@mail.com',
                'pro' => 'pro',
                'contra' => 'contra',
                'suggestions' => 'suggestions',
            ],
            $sut->jsonSerialize()
        );
    }
}
