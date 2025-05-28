<?php

declare(strict_types=1);

namespace App\Product\Service;

use App\Product\Controller\Request\ProductRequest;
use App\Product\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ProductService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(ProductRequest $request): Product
    {
        $product = new Product(
            $request->name,
            $request->cost,
            $request->tax,
            $request->measurements->weight,
            $request->measurements->height,
            $request->measurements->width,
            $request->measurements->length,
            $request->version,
            $request->description,
        );

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function update(ProductRequest $request): Product
    {
        if (!$request->id) {
            throw new \InvalidArgumentException('Product ID is missing');
        }

        $product = $this->entityManager->find(Product::class, $request->id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        $product->setName($request->name);
        $product->setCost($request->cost);
        $product->setTax($request->tax);
        $product->setWeight($request->measurements->weight);
        $product->setHeight($request->measurements->height);
        $product->setWidth($request->measurements->width);
        $product->setLength($request->measurements->length);
        $product->setVersion($request->version);
        $product->setDescription($request->description);

        $this->entityManager->flush();

        return $product;
    }
}
