<?php

declare(strict_types=1);

namespace App\Product\Service;

use App\Product\Controller\Request\FindProductRequest;
use App\Product\Controller\Request\ProductRequest;
use App\Product\Entity\Product;
use App\Product\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Query\Range;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

final readonly class ProductService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
        private PaginatedFinderInterface $productFinder,
    ) {}

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

    public function update(int $id, ProductRequest $request): Product
    {
        $product = $this->productRepository->get($id);

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

    public function search(FindProductRequest $request): array
    {
        $boolQuery = new BoolQuery();

        if (!empty($request->search)) {
            $matchQuery = new MatchQuery();
            $matchQuery->setField('name', $request->search);
            $boolQuery->addMust($matchQuery);
        }

        if ($request->minCost !== null || $request->maxCost !== null) {
            $range = new Range();
            $range->addField('cost', array_filter([
                'gte' => $request->minCost,
                'lte' => $request->maxCost,
            ]));
            $boolQuery->addFilter($range);
        }

        return $this->productFinder->find($boolQuery);
    }
}
