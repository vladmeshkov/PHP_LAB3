<?php
declare(strict_types=1);

namespace Lab3\Shop;

abstract class Product
{
    /** Максимальная скидка по промокоду для категории, в процентах. */
    protected const MAX_PROMO_DISCOUNT = 50;

    public function __construct(
        protected string $id,
        protected string $name,
        protected float $price,
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getMaxPromoDiscount(): int
    {
        return static::MAX_PROMO_DISCOUNT;
    }

    abstract public function getCategory(): string;

    /** @return array<string, string> характеристики для карточки товара */
    abstract public function getAttributes(): array;

    abstract public function getInfo(): string;
}
