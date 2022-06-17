<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 * @ORM\Table(name="reviews")
 */
class Review implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $pro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $contra;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $suggestions;

    /**
     * @ORM\OneToOne(targetEntity="Rating", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id")
     */
    private Rating $rating;

    /**
     * @ORM\OneToOne(targetEntity="Sentiment", cascade={"all"})
     * @ORM\JoinColumn(name="sentiment_id", referencedColumnName="id")
     */
    private Sentiment $sentiment;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="reviews")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private Company $company;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reviews")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private DateTime $updatedAt;

    public function __construct(
        string $title,
        ?string $pro,
        ?string $contra,
        ?string $suggestions,
        Rating $rating,
        Company $company,
        User $user
    ) {
        $this->title = $title;
        $this->pro = $pro;
        $this->contra = $contra;
        $this->suggestions = $suggestions;
        $this->rating = $rating;
        $this->company = $company;
        $this->user = $user;
        $this->sentiment = new Sentiment();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getRating(): Rating
    {
        return $this->rating;
    }

    public function getSentiment(): Sentiment
    {
        return $this->sentiment;
    }

    public function __toString(): string
    {
        return sprintf(
            "%s %s %s %s",
            $this->title,
            $this->pro,
            $this->contra,
            $this->suggestions
        );
    }

    public function jsonSerialize(): array
    {
        $json = [
         'title'        => $this->title,
         'rating'       => $this->rating->jsonSerialize(),
         'company'      => $this->company->__toString(),
         'user'         => $this->user->getUserIdentifier(),
        ];

        if ($this->pro) {
            $json['pro'] = $this->pro;
        }
        if ($this->contra) {
            $json['contra'] = $this->contra;
        }
        if ($this->suggestions) {
            $json['suggestions'] = $this->suggestions;
        }

        return $json;
    }
}
