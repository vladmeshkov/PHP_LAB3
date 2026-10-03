<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class Cart
{
    /** @var array<string, CartLine> */
    private array $lines = [];

    public function add(Product $product): void
    {
        $id = $product->getId();

        if (isset($this->lines[$id])) {
            $this->lines[$id]->increment();
            return;
        }

        $this->lines[$id] = new CartLine($product);
    }

    public function remove(string $productId): void
    {
        unset($this->lines[$productId]);
    }

    public function clear(): void
    {
        $this->lines = [];
    }

    /** @return CartLine[] */
    public function getLines(): array
    {
        return array_values($this->lines);
    }

    public function isEmpty(): bool
    {
        return $this->lines === [];
    }

    public function countItems(): int
    {
        return array_sum(array_map(
            static fn (CartLine $line): int => $line->getQuantity(),
            $this->lines
        ));
    }

    public function total(): float
    {
        return array_sum(array_map(
            static fn (CartLine $line): float => $line->subtotal(),
            $this->lines
        ));
    }
}
