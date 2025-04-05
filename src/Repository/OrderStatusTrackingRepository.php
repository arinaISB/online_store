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
}
