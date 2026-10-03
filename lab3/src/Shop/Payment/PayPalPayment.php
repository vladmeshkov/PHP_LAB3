<?php
declare(strict_types=1);

namespace Lab3\Shop\Payment;

use Lab3\Support\Money;

final class PayPalPayment extends Payment
{
    private const FEE_RATE = 0.029;

    public function __construct(private string $email)
    {
        $this->email = trim($email);
    }

    public function getMethodName(): string
    {
        return 'PayPal';
    }

    protected function validate(): ?string
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL)
            ? null
            : 'Укажите e-mail аккаунта PayPal.';
    }

    protected function fee(float $amount): float
    {
        return $amount * self::FEE_RATE;
    }

    protected function receipt(float $charged): string
    {
        return sprintf(
            'Со счёта PayPal %s списано %s (с учётом комиссии %s%%).',
            $this->email,
            Money::format($charged),
            str_replace('.', ',', (string) (self::FEE_RATE * 100))
        );
    }
}
