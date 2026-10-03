<?php
declare(strict_types=1);

namespace Lab3\Shop;

use DomainException;
use Lab3\Shop\Contracts\Discountable;
use Lab3\Support\Contracts\Journaled;
use Lab3\Support\Loggable;

abstract class Product implements Discountable, Journaled
{
    use Loggable;

    protected const MAX_DISCOUNT = 50;

    protected float $discount = 0.0;

    public function __construct(
        protected string $id,
        protected string $name,
        protected float $basePrice,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function hasDiscount(): bool
    {
        return $this->discount > 0;
    }

    public function getPrice(): float
    {
        return round($this->basePrice * (1 - $this->discount / 100), 2);
    }

    /**
     * Скидка не накапливается: новое значение заменяет предыдущее.
     * Передайте 0, чтобы вернуть исходную цену.
     */
    public function applyDiscount(float $percent): void
    {
        if ($percent < 0 || $percent > static::MAX_DISCOUNT) {
            throw new DomainException(sprintf(
                'Для категории «%s» допустима скидка от 0 до %d%%.',
                $this->getCategory(),
                static::MAX_DISCOUNT
            ));
        }

        $this->discount = $percent;

        $this->log($percent > 0
            ? sprintf('Скидка %s%% применена: %s', $percent, $this->name)
            : sprintf('Скидка отменена: %s', $this->name));
    }

    abstract public function getCategory(): string;

    /** @return array<string, string> характеристики для карточки товара */
    abstract public function getAttributes(): array;

    abstract public function getInfo(): string;
}
