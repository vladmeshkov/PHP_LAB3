<?php
declare(strict_types=1);

namespace Lab3\Shop\Payment;

final class PaymentResult
{
    private function __construct(
        private bool $successful,
        private string $message,
        private ?string $transactionId = null,
        private float $charged = 0.0,
    ) {
    }

    public static function success(string $transactionId, float $charged, string $message): self
    {
        return new self(true, $message, $transactionId, $charged);
    }

    public static function failure(string $message): self
    {
        return new self(false, $message);
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getCharged(): float
    {
        return $this->charged;
    }
}
