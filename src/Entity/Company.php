<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ORM\Table(name="companies")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $industry;

    /**
     * @ORM\OneToMany(targetEntity="Review", mappedBy="company")
     */
    private Collection $reviews;

    /**
     * @ORM\OneToOne(targetEntity="CompanyScore", cascade={"all"}, orphanRemoval=true))
     * @ORM\JoinColumn(name="score_id", referencedColumnName="id")
     */
    private CompanyScore $score;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private DateTime $updatedAt;


    public function __construct(string $name, string $slug, string $city, string $country, string $industry)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->city = $city;
        $this->country = $country;
        $this->industry = $industry;
        $this->score = new CompanyScore();
        $this->reviews = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getScore(): ?CompanyScore
    {
        return $this->score;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
