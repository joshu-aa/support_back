<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    // /**
    //  * @return Card[] Returns an array of Card objects
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

    public function getCards($data)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ticketStatus = :ticketStatus')
            ->setParameters(['ticketStatus' => $data["ticketStatus"]])
            ->getQuery()
            ->getResult()
        ;
    }

    public function searchTickets($data)
    {
        //sort by
        if($data["sortBy"] == "desc") {
            $builder = $this->createQueryBuilder('c')
                ->orderBy('c.timestamp', 'DESC');
        } else {
            $builder = $this->createQueryBuilder('c')
                ->orderBy('c.timestamp', 'ASC');
        }

        $parameters["ticketStatus"] = $data["ticketStatus"];
        $parameters["assignedGroup"] = $data["assignedGroup"];


        if($data["searchParam"]) {

            if(strlen($data["searchParam"]) < 10) {
                if($data["location"] != "All") {
                    $builder = $builder
                    ->andWhere('c.location = :location')
                    ->andWhere('c.unitNumber = :unitNumber');
                    $parameters['unitNumber'] = $data["searchParam"];
                    $parameters['location'] = $data["location"];
                } else {
                    $builder = $builder
                        ->andWhere('c.unitNumber = :unitNumber');
                        $parameters['unitNumber'] = $data["searchParam"];
                }

            } else if(strlen($data["searchParam"]) > 10) {
                $builder = $builder
                    ->andWhere('c.referenceNumber = :referenceNumber');

                    $parameters['referenceNumber'] = $data["searchParam"];
            }else {

                $builder = $builder
                    ->andWhere('c.subscriberId = :subscriberId');

                    $parameters['subscriberId'] = $data["searchParam"];
            }
        } else {
                if($data["location"] !== "All") {
                    $builder = $builder
                    ->andWhere('c.location = :location');

                    $parameters['location'] = $data["location"];
                }
        }

        return $builder
            ->andWhere('c.ticketStatus = :ticketStatus')
            ->andWhere('c.assignedGroup = :assignedGroup')
            ->setParameters($parameters)
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function getLatestReferenceNumber()
    {
        return $this->createQueryBuilder('c')
            ->select('c.referenceNumber')
            ->andWhere('c.referenceNumber != :referenceNumber')
            ->setMaxResults(1)
            ->orderBy('c.id', 'DESC')
            ->setParameters(['referenceNumber' => 0])
            ->getQuery()
            ->getResult()
        ;
    }
}
