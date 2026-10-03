<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class CartLine
{
    public function __construct(
        private Product $product,
        private int $quantity = 1,
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function increment(): void
    {
        $this->quantity++;
    }

    public function subtotal(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
