<?php

declare(strict_types=1);

namespace App\Cart\Controller;

use App\Cart\Controller\Request\CreateCartRequest;
use App\Cart\Service\CartService;
use App\Product\Entity\Product;
use App\User\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsController]
final readonly class CartController
{
    public function __construct(
        private CartService $cartService,
        private NormalizerInterface $serializer,
    ) {}

    #[Route('carts', methods: [Request::METHOD_POST])]
    #[IsGranted('ROLE_USER')]
    public function create(#[MapRequestPayload] CreateCartRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->cartService->addProduct(
            $user,
            $request,
        );

        return new JsonResponse('Product added to cart', Response::HTTP_CREATED);
    }

    #[Route('/carts', methods: [Request::METHOD_GET])]
    #[IsGranted('ROLE_USER')]
    public function show(#[CurrentUser] User $user): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize($this->cartService->show($user), 'json'),
            Response::HTTP_OK,
        );
    }

    #[Route('/carts/{productId}', methods: [Request::METHOD_DELETE])]
    #[IsGranted('ROLE_USER')]
    public function delete(
        #[MapEntity(id: 'productId')]
        Product $product,
        #[CurrentUser]
        User $user,
    ): JsonResponse {
        $this->cartService->removeProductFromCart($user, $product->getId());

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT,
        );
    }
}
