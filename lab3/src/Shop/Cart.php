<?php
declare(strict_types=1);

namespace Lab3\Shop;

use DomainException;
use Lab3\Shop\Contracts\Discountable;
use Lab3\Support\Contracts\Journaled;
use Lab3\Support\Loggable;

final class Cart implements Discountable, Journaled
{
    use Loggable;

    /** @var array<string, CartLine> */
    private array $lines = [];

    private float $discount = 0.0;
    private ?string $promoCode = null;

    public function add(Product $product, int $quantity = 1): void
    {
        $id = $product->getId();
        $line = $this->lines[$id] ?? null;
        $newQuantity = ($line?->getQuantity() ?? 0) + $quantity;

        $this->assertQuantity($newQuantity);

        if ($line === null) {
            $this->lines[$id] = new CartLine($product, $newQuantity);
        } else {
            $line->setQuantity($newQuantity);
        }

        $this->log(sprintf('В корзину: %s × %d', $product->getName(), $quantity));
    }

    public function setQuantity(string $productId, int $quantity): void
    {
        $this->assertQuantity($quantity);

        if (!isset($this->lines[$productId])) {
            throw new DomainException('Этого товара нет в корзине.');
        }

        $this->lines[$productId]->setQuantity($quantity);
    }

    public function remove(string $productId): void
    {
        unset($this->lines[$productId]);

        if ($this->lines === []) {
            $this->resetPromo();
        }
    }

    public function clear(): void
    {
        $this->lines = [];
        $this->resetPromo();
    }

    public function applyDiscount(float $percent): void
    {
        if ($percent < 0 || $percent > 100) {
            throw new DomainException('Скидка должна быть от 0 до 100%.');
        }

        $this->discount = $percent;
    }

    public function applyPromo(string $code): void
    {
        $percent = PromoCodes::percentFor($code);

        if ($percent === null) {
            $this->log('Промокод отклонён: ' . strtoupper(trim($code)));
            throw new DomainException('Такого промокода не существует.');
        }

        $this->applyDiscount($percent);
        $this->promoCode = strtoupper(trim($code));
        $this->log("Промокод {$this->promoCode} применён (−{$percent}%)");
    }

    public function removePromo(): void
    {
        $this->resetPromo();
    }

    public function getPromoCode(): ?string
    {
        return $this->promoCode;
    }

    public function getDiscount(): float
    {
        return $this->discount;
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

    public function lineTotal(CartLine $line): float
    {
        return $line->total($this->discount);
    }

    public function subtotal(): float
    {
        return array_sum(array_map(
            static fn (CartLine $line): float => $line->subtotal(),
            $this->lines
        ));
    }

    public function total(): float
    {
        return array_sum(array_map(
            fn (CartLine $line): float => $line->total($this->discount),
            $this->lines
        ));
    }

    public function discountAmount(): float
    {
        return round($this->subtotal() - $this->total(), 2);
    }

    private function resetPromo(): void
    {
        $this->discount = 0.0;
        $this->promoCode = null;
    }

    private function assertQuantity(int $quantity): void
    {
        if ($quantity < 1 || $quantity > CartLine::MAX_QUANTITY) {
            throw new DomainException('Количество должно быть от 1 до ' . CartLine::MAX_QUANTITY . '.');
        }
    }
}
