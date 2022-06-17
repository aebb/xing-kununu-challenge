<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class RepositoryFactory
{
    protected const REPOSITORY_USER             = 'user-repository';
    protected const REPOSITORY_COMPANY          = 'company-repository';
    protected const REPOSITORY_COMPANY_SCORE    = 'company-score-repository';
    protected const REPOSITORY_REVIEW           = 'review-repository';
    protected const REPOSITORY_SENTIMENT        = 'sentiment-repository';

    protected ManagerRegistry $registry;
    protected EntityManagerInterface $entityManager;

    protected array $repositories;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->registry = $registry;
        $this->entityManager = $entityManager;
    }

    /** @codeCoverageIgnore */
    protected function createRepository(
        string $repository,
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        ...$args
    ) {
        return new $repository($registry, $entityManager, ...$args);
    }

    public function getUserRepository(): UserRepository
    {
        if (!isset($this->repositories[self::REPOSITORY_USER])) {
            $this->repositories[self::REPOSITORY_USER] = $this->createRepository(
                UserRepository::class,
                $this->registry,
                $this->entityManager
            );
        }
        return $this->repositories[self::REPOSITORY_USER];
    }

    public function getCompanyRepository(): CompanyRepository
    {
        if (!isset($this->repositories[self::REPOSITORY_COMPANY])) {
            $this->repositories[self::REPOSITORY_COMPANY] = $this->createRepository(
                CompanyRepository::class,
                $this->registry,
                $this->entityManager
            );
        }
        return $this->repositories[self::REPOSITORY_COMPANY];
    }

    public function getReviewRepository(): ReviewRepository
    {
        if (!isset($this->repositories[self::REPOSITORY_REVIEW])) {
            $this->repositories[self::REPOSITORY_REVIEW] = $this->createRepository(
                ReviewRepository::class,
                $this->registry,
                $this->entityManager
            );
        }
        return $this->repositories[self::REPOSITORY_REVIEW];
    }

    public function getCompanyScoreRepository(): CompanyScoreRepository
    {
        if (!isset($this->repositories[self::REPOSITORY_COMPANY_SCORE])) {
            $this->repositories[self::REPOSITORY_COMPANY_SCORE] = $this->createRepository(
                CompanyScoreRepository::class,
                $this->registry,
                $this->entityManager
            );
        }
        return $this->repositories[self::REPOSITORY_COMPANY_SCORE];
    }

    public function getSentimentRepository(): SentimentRepository
    {
        if (!isset($this->repositories[self::REPOSITORY_SENTIMENT])) {
            $this->repositories[self::REPOSITORY_SENTIMENT] = $this->createRepository(
                SentimentRepository::class,
                $this->registry,
                $this->entityManager
            );
        }
        return $this->repositories[self::REPOSITORY_SENTIMENT];
    }
}
