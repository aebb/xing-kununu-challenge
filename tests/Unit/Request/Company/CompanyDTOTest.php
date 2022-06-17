<?php

namespace App\Tests\Unit\Request\Company;

use App\Entity\Company;
use App\Request\Company\CompanyDTO;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Request\Company\CompanyDTO */
class CompanyDTOTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
     */
    public function testCompanyDTO()
    {
        $value = 'foo-bar';
        $company = new Company($value, $value, $value, $value, $value);
        $array = [
            [
              0 => $company,
              'score' => 500
            ]
        ];

        $sut = new CompanyDTO($array);
        $this->assertEquals([['company' => 'foo-bar', 'score' => 5]], $sut->jsonSerialize());
    }
}
