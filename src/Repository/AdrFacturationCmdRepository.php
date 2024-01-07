<?php

namespace App\Repository;

use App\Entity\AdrFacturationCmd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdrFacturationCmd>
 *
 * @method AdrFacturationCmd|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdrFacturationCmd|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdrFacturationCmd[]    findAll()
 * @method AdrFacturationCmd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdrFacturationCmdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdrFacturationCmd::class);
    }

//    /**
//     * @return AdrFacturationCmd[] Returns an array of AdrFacturationCmd objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdrFacturationCmd
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
