<?php
declare(strict_types=1);

namespace Lab3\Shop\Payment;

use Lab3\Shop\Contracts\Payable;
use Lab3\Support\Contracts\Journaled;
use Lab3\Support\Loggable;
use Lab3\Support\Money;

abstract class Payment implements Payable, Journaled
{
    use Loggable;

    public function pay(float $amount): PaymentResult
    {
        if ($amount <= 0) {
            return PaymentResult::failure('Сумма к оплате должна быть больше нуля.');
        }

        $problem = $this->validate();
        if ($problem !== null) {
            $this->log("{$this->getMethodName()}: платёж отклонён. {$problem}");
            return PaymentResult::failure($problem);
        }

        $charged = round($amount + $this->fee($amount), 2);
        $transactionId = $this->newTransactionId();

        $this->log(sprintf(
            '%s: списано %s, операция %s',
            $this->getMethodName(),
            Money::format($charged),
            $transactionId
        ));

        return PaymentResult::success($transactionId, $charged, $this->receipt($charged));
    }

    abstract public function getMethodName(): string;

    /** Возвращает текст ошибки или null, если данные корректны. */
    abstract protected function validate(): ?string;

    abstract protected function receipt(float $charged): string;

    protected function fee(float $amount): float
    {
        return 0.0;
    }

    private function newTransactionId(): string
    {
        return strtoupper(substr(static::class, strrpos(static::class, '\\') + 1, 2))
            . '-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
