<?php

namespace App\Repository;

use App\Entity\AdrLivraisonUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdrLivraisonUser>
 *
 * @method AdrLivraisonUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdrLivraisonUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdrLivraisonUser[]    findAll()
 * @method AdrLivraisonUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdrLivraisonUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdrLivraisonUser::class);
    }

//    /**
//     * @return AdrLivraisonUser[] Returns an array of AdrLivraisonUser objects
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

//    public function findOneBySomeField($value): ?AdrLivraisonUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
