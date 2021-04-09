<?php

namespace App\Repository;

use App\Entity\OrderSlip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderSlip|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderSlip|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderSlip[]    findAll()
 * @method OrderSlip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderSlipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderSlip::class);
    }

    // /**
    //  * @return OrderSlip[] Returns an array of OrderSlip objects
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
    public function findOneBySomeField($value): ?OrderSlip
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
