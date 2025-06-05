<?php

declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Controller\Request\FindProductRequest;
use App\Product\Controller\Request\ProductRequest;
use App\Product\Entity\Product;
use App\Product\Service\ProductService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsController]
final readonly class ProductController
{
    public function __construct(private ProductService $productService, private NormalizerInterface $serializer) {}

    #[Route('products', methods: [Request::METHOD_POST])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(#[MapRequestPayload] ProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request);

        return new JsonResponse(['product_id' => $product->getId()], Response::HTTP_CREATED);
    }

    #[Route('products/{id}', methods: [Request::METHOD_PUT])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, #[MapRequestPayload] ProductRequest $request): JsonResponse
    {
        $product = $this->productService->update($id, $request);

        return new JsonResponse(['product_id' => $product->getId()], Response::HTTP_OK);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('products/search', methods: [Request::METHOD_GET])]
    public function search(#[MapQueryString] FindProductRequest $request): JsonResponse
    {
        $response = $this->productService->search($request);

        return new JsonResponse($this->serializer->normalize($response, 'json'), Response::HTTP_OK);
    }

    #[Route('products/{id}', methods: [Request::METHOD_GET])]
    public function get(#[MapEntity] Product $product): JsonResponse
    {
        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);
    }
}
