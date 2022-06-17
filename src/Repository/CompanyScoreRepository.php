<?php

namespace App\Repository;

use App\Entity\CompanyScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyScore[]    findAll()
 * @method CompanyScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class CompanyScoreRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, CompanyScore::class);
        $this->entityManager = $entityManager;
    }

    public function persist(CompanyScore $entity): CompanyScore
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function update(CompanyScore $entity): CompanyScore
    {
        $this->entityManager->flush();
        return $entity;
    }
}
