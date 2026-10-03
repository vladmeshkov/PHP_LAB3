<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class Electronic extends Product
{
    // На технику скидки ограничены жёстче, чем на остальные товары.
    protected const MAX_DISCOUNT = 30;

    public function __construct(
        string $id,
        string $name,
        float $basePrice,
        private string $brand,
        private int $warrantyMonths,
    ) {
        parent::__construct($id, $name, $basePrice);
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
