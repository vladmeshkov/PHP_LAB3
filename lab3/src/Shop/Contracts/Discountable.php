<?php
declare(strict_types=1);

namespace Lab3\Shop\Contracts;

interface Discountable
{
    public function applyDiscount(float $percent): void;
}
