<?php
declare(strict_types=1);

namespace Lab3\Support;

final class Money
{
    public static function format(float $amount): string
    {
        return number_format($amount, 2, ',', "\u{00A0}") . "\u{00A0}₽";
    }
}
