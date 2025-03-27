<?php

namespace App\Repository;

use App\Entity\OrderStatusTracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderStatusTracking>
 *
 * @method OrderStatusTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderStatusTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderStatusTracking[]    findAll()
 * @method OrderStatusTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderStatusTrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatusTracking::class);
    }

//    /**
//     * @return OrderStatusTracking[] Returns an array of OrderStatusTracking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrderStatusTracking
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
