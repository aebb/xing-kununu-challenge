<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class CompanyRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Company::class);
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Company
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function persist(Company $entity): Company
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function listBest(string $search, int $start, int $count): array
    {
        $builder = $this->createQueryBuilder('c');
        return $builder
            ->select('c, s.score')
            ->innerJoin('c.score', 's')
            ->andWhere('c.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->setFirstResult($start)
            ->setMaxResults($count)
            ->orderBy('s.score', 'DESC')
            ->getQuery()
            ->execute();
    }

    public function update(Company $entity): Company
    {
        $this->entityManager->flush();
        return $entity;
    }
}
