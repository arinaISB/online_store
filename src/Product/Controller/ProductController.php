<?php

declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Controller\Request\ProductRequest;
use App\Product\Entity\Product;
use App\Product\Service\ProductService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final readonly class ProductController
{
    public function __construct(private ProductService $productService)
    {
    }

    #[Route('', methods: [Request::METHOD_POST])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(#[MapRequestPayload] ProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request);

        return new JsonResponse(['product_id' => $product->getId()], Response::HTTP_CREATED);
    }

    #[Route('', methods: [Request::METHOD_PUT])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(#[MapRequestPayload] ProductRequest $request): JsonResponse
    {
        $product = $this->productService->update($request);

        return new JsonResponse(['product_id' => $product->getId()], Response::HTTP_OK);
    }

    #[Route('/{id}', methods: [Request::METHOD_GET])]
    public function get(#[MapEntity] Product $product): JsonResponse
    {
        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);
    }
}
