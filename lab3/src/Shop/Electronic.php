<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class Electronic extends Product
{
    // По промокоду на технику скидка не больше 30%, на остальные товары — до 50%.
    protected const MAX_PROMO_DISCOUNT = 30;

    public function __construct(
        string $id,
        string $name,
        float $price,
        private string $brand,
        private int $warrantyMonths,
    ) {
        parent::__construct($id, $name, $price);
    }

    public function getWarrantyPeriod(): int
    {
        return $this->warrantyMonths;
    }

    public function getCategory(): string
    {
        return 'Электроника';
    }

    public function getAttributes(): array
    {
        return [
            'Бренд'    => $this->brand,
            'Гарантия' => $this->warrantyMonths . ' мес.',
        ];
    }

    public function getInfo(): string
    {
        return "{$this->name} ({$this->brand}), гарантия {$this->warrantyMonths} мес.";
    }
}
