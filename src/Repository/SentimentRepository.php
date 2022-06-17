<?php

namespace App\Repository;

use App\Entity\Sentiment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sentiment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sentiment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sentiment[]    findAll()
 * @method Sentiment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class SentimentRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Sentiment::class);
        $this->entityManager = $entityManager;
    }

    public function persist(Sentiment $entity): Sentiment
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function update(Sentiment $entity): Sentiment
    {
        $this->entityManager->flush();
        return $entity;
    }
}
