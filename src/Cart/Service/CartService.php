<?php

declare(strict_types=1);

namespace App\Cart\Service;

use App\Cart\Controller\Request\CreateCartRequest;
use App\Cart\Controller\Response\CartItemResponse;
use App\Cart\Controller\Response\ShowCart;
use App\Cart\Entity\Cart;
use App\Cart\Entity\CartItem;
use App\Product\Repository\ProductRepository;
use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CartService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductRepository $productRepository,
    ) {}

    public function addProduct(User $user, CreateCartRequest $request): void
    {
        $productId = $request->productId;
        $quantity = $request->quantity;

        $product = $this->productRepository->get($productId);

        $cart = $user->getCart();

        if (!$cart) {
            $cart = new Cart($user);
            $user->setCart($cart);
            $this->entityManager->persist($cart);
        }

        /** @var CartItem $existingItem */
        $existingItem = $cart->getCartItems()
            ->filter(static fn(CartItem $item) => $item->getProduct()->getId() === $productId)
            ->first();

        if ($existingItem) {
            $existingItem->setQuantity($existingItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem($cart, $product, $quantity);
            $cart->addCartItem($cartItem);
            $this->entityManager->persist($cartItem);
        }

        $this->entityManager->flush();
    }

    public function show(User $user): ShowCart
    {
        $cart = $user->getCart();

        if (!$cart) {
            $cart = new Cart($user);
            $user->setCart($cart);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        $items = [];
        $total = 0;

        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            $price = $cartItem->getProduct()->getCost();
            $subtotal = $price * $cartItem->getQuantity();

            $items[] = new CartItemResponse(
                $cartItem->getProduct()->getName(),
                $cartItem->getQuantity(),
                $price,
                $subtotal,
            );

            $total += $subtotal;
        }

        return new ShowCart($items, $total);
    }

    public function removeProductFromCart(User $user, int $productId): void
    {
        $cart = $user->getCart();

        $item = $cart->getCartItems()
            ->filter(static fn(CartItem $item) => $item->getProduct()->getId() === $productId)
            ->first();

        $cart->removeCartItem($item);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }
}
