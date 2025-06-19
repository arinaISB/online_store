<?php

declare(strict_types=1);

namespace App\Product\Service;

use App\Product\Controller\Request\FindProductRequest;
use App\Product\Controller\Request\ProductRequest;
use App\Product\Controller\Response\SearchResult;
use App\Product\Entity\Product;
use App\Product\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Fuzzy;
use Elastica\Query\Range;
use FOS\ElasticaBundle\Finder\HybridFinderInterface;

final readonly class ProductService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
        private HybridFinderInterface $productFinder,
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

    public function update(Product $product, ProductRequest $request): Product
    {
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
            $fuzzy = new Fuzzy('name', $request->search);
            $fuzzy->setFieldOption('fuzziness', 'AUTO');
            $fuzzy->setFieldOption('prefix_length', 2);
            $fuzzy->setFieldOption('transpositions', true);
            $boolQuery->addShould($fuzzy);
        }

        if ($request->minCost !== null || $request->maxCost !== null) {
            $range = new Range();
            $range->addField('cost', array_filter([
                'gte' => $request->minCost,
                'lte' => $request->maxCost,
            ]));
            $boolQuery->addFilter($range);
        }

        $query = new Query($boolQuery);
        $query->setSize(20);
        $query->setHighlight([
            'fields' => [
                'name' => ['fragment_size' => 150],
                'description' => ['fragment_size' => 200],
            ],
        ]);

        $hybridResults = $this->productFinder->findHybrid($query);

        $results = [];
        foreach ($hybridResults as $hybrid) {
            $product = $hybrid->getTransformed();
            $result = $hybrid->getResult();
            $hit = $result->getHit();

            if (!$product instanceof Product) {
                continue;
            }

            $results[] = new SearchResult(
                product: $hybrid->getTransformed(),
                score: $hit['_score'] ?? 0.0,
                highlight: $hit['highlight'] ?? [],
            );
        }

        return $results;
    }
}
