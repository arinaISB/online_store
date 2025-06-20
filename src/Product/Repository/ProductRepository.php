<?php

declare(strict_types=1);

namespace App\Product\Repository;

use App\Product\Entity\Product;
use App\Product\Exception\ProductNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function get(int $id): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw ProductNotFoundException::withId($id);
        }

        return $product;
    }
}
