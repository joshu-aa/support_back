<?php

namespace App\Repository;

use App\Entity\PendingRegistrant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PendingRegistrant|null find($id, $lockMode = null, $lockVersion = null)
 * @method PendingRegistrant|null findOneBy(array $criteria, array $orderBy = null)
 * @method PendingRegistrant[]    findAll()
 * @method PendingRegistrant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PendingRegistrantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PendingRegistrant::class);
    }

    // /**
    //  * @return PendingRegistrant[] Returns an array of PendingRegistrant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PendingRegistrant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
