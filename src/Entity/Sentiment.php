<?php

namespace App\Entity;

use DateTime;
use App\Repository\SentimentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SentimentRepository::class)
 * @ORM\Table(name="reviews_sentiment")
 */
class Sentiment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    private float $positive;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    private float $negative;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    private float $abusive;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $analyzed;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->positive = 0.0;
        $this->negative = 0.0;
        $this->abusive = 0.0;
        $this->analyzed = false;
        $this->updatedAt = new DateTime();
    }

    public function updateSentiment(float $positive, float $negative, float $abusive): Sentiment
    {
        $this->positive = $positive;
        $this->negative = $negative;
        $this->abusive = $abusive;
        $this->analyzed = true;
        $this->updatedAt = new DateTime();
        return $this;
    }
}
