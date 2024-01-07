<?php

namespace App\Repository;

use App\Entity\AdrLivraisonCmd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdrLivraisonCmd>
 *
 * @method AdrLivraisonCmd|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdrLivraisonCmd|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdrLivraisonCmd[]    findAll()
 * @method AdrLivraisonCmd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdrLivraisonCmdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdrLivraisonCmd::class);
    }

//    /**
//     * @return AdrLivraisonCmd[] Returns an array of AdrLivraisonCmd objects
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

//    public function findOneBySomeField($value): ?AdrLivraisonCmd
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
