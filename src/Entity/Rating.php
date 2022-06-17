<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 * @ORM\Table(name="ratings")
 */
class Rating implements JsonSerializable
{
    public const METRICS_COUNT = 4;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $culture;

    /**
     * @ORM\Column(type="integer")
     */
    private int $management;

    /**
     * @ORM\Column(type="integer", name="work_life_balance")
     */
    private int $workLifeBalance;

    /**
     * @ORM\Column(type="integer", name="career_development")
     */
    private int $careerDevelopment;

    public function __construct(int $culture, int $management, int $workLifeBalance, int $careerDevelopment)
    {
        $this->culture = $culture;
        $this->management = $management;
        $this->workLifeBalance = $workLifeBalance;
        $this->careerDevelopment = $careerDevelopment;
    }

    public function getScore(): int
    {
        return $this->culture + $this->management + $this->workLifeBalance + $this->careerDevelopment;
    }


    public function jsonSerialize(): array
    {
        return [
            'culture'           => $this->culture,
            'management'        => $this->management,
            'workLifeBalance'   => $this->workLifeBalance,
            'careerDevelopment' => $this->careerDevelopment,
        ];
    }
}
