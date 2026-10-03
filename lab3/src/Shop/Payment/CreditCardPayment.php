<?php
declare(strict_types=1);

namespace Lab3\Shop\Payment;

use DateTimeImmutable;
use Lab3\Support\Money;

final class CreditCardPayment extends Payment
{
    private string $number;

    public function __construct(
        string $number,
        private string $holder,
        private string $expiry,
        private string $cvv,
    ) {
        $this->number = preg_replace('/\D+/', '', $number) ?? '';
    }

    public function getMethodName(): string
    {
        return 'Банковская карта';
    }

    protected function validate(): ?string
    {
        if (!self::passesLuhn($this->number)) {
            return 'Номер карты указан неверно.';
        }

        if (!preg_match('/^[\p{L}][\p{L} .\'-]+$/u', trim($this->holder))) {
            return 'Укажите имя держателя карты.';
        }

        if ($this->isExpired()) {
            return 'Срок действия карты истёк или указан в неверном формате (ожидается ММ/ГГ).';
        }

        if (!preg_match('/^\d{3}$/', $this->cvv)) {
            return 'CVV должен состоять из трёх цифр.';
        }

        return null;
    }

    protected function receipt(float $charged): string
    {
        return sprintf(
            'С карты •••• %s списано %s.',
            substr($this->number, -4),
            Money::format($charged)
        );
    }

    private function isExpired(): bool
    {
        if (!preg_match('#^(0[1-9]|1[0-2])/(\d{2})$#', trim($this->expiry), $m)) {
            return true;
        }

        $lastDay = new DateTimeImmutable("20{$m[2]}-{$m[1]}-01 last day of this month 23:59:59");

        return $lastDay < new DateTimeImmutable();
    }

    private static function passesLuhn(string $digits): bool
    {
        $length = strlen($digits);
        if ($length < 13 || $length > 19) {
            return false;
        }

        $sum = 0;
        foreach (array_reverse(str_split($digits)) as $position => $char) {
            $digit = (int) $char;
            if ($position % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return $sum % 10 === 0;
    }
}
