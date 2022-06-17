<?php

namespace App\Request\Company;

use JsonSerializable;

class CompanyDTO implements JsonSerializable
{
    private const SPLIT = 100;

    private array $entities;

    public function __construct(array $entities)
    {
        $this->entities = $entities;
    }

    public function jsonSerialize(): array
    {
        return array_map(function ($element) {
            return  [
                'company' => $element[0]->__toString(),
                'score'   => ($element['score'] / self::SPLIT)
            ];
        }, $this->entities);
    }
}
