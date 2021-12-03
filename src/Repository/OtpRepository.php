<?php

namespace App\Repository;

use App\Entity\Otp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Otp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Otp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Otp[]    findAll()
 * @method Otp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OtpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Otp::class);
    }

    // /**
    //  * @return Otp[] Returns an array of Otp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Otp
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
