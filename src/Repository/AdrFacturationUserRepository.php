<?php

namespace App\Repository;

use App\Entity\AdrFacturationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdrFacturationUser>
 *
 * @method AdrFacturationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdrFacturationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdrFacturationUser[]    findAll()
 * @method AdrFacturationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdrFacturationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdrFacturationUser::class);
    }

    // Méthode pour récupérer les adresses de livraison d'un utilisateur donné
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

    }

//    /**
//     * @return AdrFacturationUser[] Returns an array of AdrFacturationUser objects
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

//    public function findOneBySomeField($value): ?AdrFacturationUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
