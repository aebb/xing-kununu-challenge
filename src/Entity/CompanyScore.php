<?php

namespace App\Entity;

use App\Repository\CompanyScoreRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyScoreRepository::class)
 * @ORM\Table(
 *     name="companies_score",
 *     indexes={
 *          @ORM\Index(name="score_index", columns={"score"})
 *     }
 *  )
 */
class CompanyScore
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $score;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private DateTime $updatedAt;

    public function __construct(int $score = 0)
    {
        $this->score = $score;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function updateScore(int $score): void
    {
        $this->score = $score;
        $this->updatedAt = new DateTime();
    }
}
