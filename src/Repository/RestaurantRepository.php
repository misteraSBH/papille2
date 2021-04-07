<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    // /**
    //  * @return Restaurant[] Returns an array of Restaurant objects
    //  */

    public function findAllRestaurantsByName($name, $address)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :name AND r.address LIKE :address')
            ->setParameter('name', '%'.$name.'%')
            ->setParameter('address', '%'.$address.'%')
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getQueryFindAllRestaurantsByName($name, $address)
    {
        $qb = $this->createQueryBuilder('r')
        ;

            $qb
                ->andWhere('r.name LIKE :name AND r.address LIKE :address')
                ->setParameter('name', '%'.$name.'%')
                ->setParameter('address', '%'.$address.'%');


        return $qb
            ->orderBy('r.name','ASC')
            ->getQuery()
            ;
    }

    public function findAllRestaurantsByNameorType($search,$opening)
    {
        if(!empty($search) OR !empty($opening)){
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :search OR r.type LIKE :search')
            ->andWhere('r.opening LIKE :opening OR r.opening = 3')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('opening', '%'.$opening.'%')
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        } else {
            return $this->findAll();
        }
    }

}
