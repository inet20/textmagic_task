<?php

namespace App\Repository;

use App\Entity\QuizResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizResult>
 */
class QuizResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizResult::class);
    }

    public function getById(int $id): ?QuizResult
    {
        return $this->createQueryBuilder('qr')
            ->andWhere('qr.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->setCacheable(true)
            ->setCacheRegion('region')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
