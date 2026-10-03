<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class PromoCodes
{
    private const CODES = [
        'PROMO10' => 10,
        'PROMO20' => 20,
        'PROMO50' => 50,
    ];

    public static function percentFor(string $code): ?int
    {
        return self::CODES[strtoupper(trim($code))] ?? null;
    }
}
