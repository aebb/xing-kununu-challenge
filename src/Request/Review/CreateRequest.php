<?php

namespace App\Request\Review;

use App\Request\RequestModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest extends RequestModel
{
    /**
     * @Assert\NotBlank(message = "title parameter must be present")
     * @Assert\Length(
     *      min = 10,
     *      max = 200,
     *      minMessage = "Your title must be at least {{ limit }} characters long",
     *      maxMessage = "Your title name cannot be longer than {{ limit }} characters"
     * )
     */
    protected ?string $title;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Your pro cannot be longer than {{ limit }} characters"
     * )
     */
    protected ?string $pro;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Your contra cannot be longer than {{ limit }} characters"
     * )
     */
    protected ?string $contra;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Your suggestions cannot be longer than {{ limit }} characters"
     * )
     */
    protected ?string $suggestions;

    /**
     * @Assert\NotBlank(message = "company parameter must be present")
     * @Assert\Regex(pattern = "/^\d+$/", message = "company parameter must be an integer")
     * @Assert\Positive(message = "company parameter must be a positive integer")
     */
    protected ?string $company;

    /**
     * @Assert\NotBlank(message = "culture parameter must be present")
     * @Assert\Regex(pattern = "/^\d+$/", message = "culture parameter must be an integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "Culture rating must be between {{ min }} and {{ max }}",
     * )
     */
    protected ?string $culture;

    /**
     * @Assert\NotBlank(message = "management parameter must be present")
     * @Assert\Regex(pattern = "/^\d+$/", message = "management parameter must be an integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "Management rating must be between {{ min }} and {{ max }}",
     * )
     */
    protected ?string $management;

    /**
     * @Assert\NotBlank(message = "Work life balance parameter must be present")
     * @Assert\Regex(pattern = "/^\d+$/", message = "Work life balance parameter must be an integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "Work life balance rating must be between {{ min }} and {{ max }}",
     * )
     */
    protected ?string $workLifeBalance;

    /**
     * @Assert\NotBlank(message = "Career development parameter must be present")
     * @Assert\Regex(pattern = "/^\d+$/", message = "Career development parameter must be an integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "Career Development rating must be between {{ min }} and {{ max }}",
     * )
     */
    protected ?string $careerDevelopment;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $body = json_decode($request->getContent(), true);
        $this->title                = $body['title'] ?? null;
        $this->pro                  = $body['pro'] ?? null;
        $this->contra               = $body['contra'] ?? null;
        $this->suggestions          = $body['suggestions'] ?? null;
        $this->company              = $body['company'] ?? null;
        $this->culture              = $body['rating']['culture'] ?? null;
        $this->management           = $body['rating']['management'] ?? null;
        $this->workLifeBalance      = $body['rating']['workLifeBalance'] ?? null;
        $this->careerDevelopment    = $body['rating']['careerDevelopment'] ?? null;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPro(): ?string
    {
        return $this->pro ?? null;
    }

    public function getContra(): ?string
    {
        return $this->contra ?? null;
    }

    public function getSuggestions(): ?string
    {
        return $this->suggestions ?? null;
    }

    public function getCompanyId(): int
    {
        return $this->company;
    }

    public function getCulture(): int
    {
        return $this->culture;
    }

    public function getManagement(): int
    {
        return $this->management;
    }

    public function getWorkLifeBalance(): int
    {
        return $this->workLifeBalance;
    }

    public function getCareerDevelopment(): int
    {
        return $this->careerDevelopment;
    }
}
