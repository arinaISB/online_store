<?php

declare(strict_types=1);

namespace App\Order\Repository;

use App\Order\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderItem>
 *
 * @method OrderItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderItem[]    findAll()
 * @method OrderItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    public function findAllSoldItems(?\DateTimeImmutable $from = null, ?\DateTimeImmutable $to = null): array
    {
        $builder = $this->createQueryBuilder('oi')
            ->innerJoin('oi.product', 'p')
            ->innerJoin('oi.order', 'o')
            ->innerJoin('o.user', 'u')
            ->addSelect('p', 'u', 'o');

        if ($from !== null) {
            $builder->andWhere('o.createdAt >= :from')
                ->setParameter('from', $from);
        }

        if ($to !== null) {
            $builder->andWhere('o.createdAt <= :to')
                ->setParameter('to', $to);
        }

        return $builder->getQuery()->getResult();
    }
}
