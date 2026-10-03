<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class CartLine
{
    public const MAX_QUANTITY = 99;

    public function __construct(
        private Product $product,
        private int $quantity,
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

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function subtotal(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }

    /** Сумма строки с учётом скидки; для категории действует свой потолок. */
    public function total(float $cartDiscount): float
    {
        $percent = min($cartDiscount, $this->product->getMaxPromoDiscount());

        return round($this->subtotal() * (1 - $percent / 100), 2);
    }
}
