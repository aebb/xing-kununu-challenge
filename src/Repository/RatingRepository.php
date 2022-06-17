<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class RatingRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Rating::class);
        $this->entityManager = $entityManager;
    }

    public function persist(Rating $entity): Rating
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }
}
