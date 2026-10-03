<?php
declare(strict_types=1);

namespace Lab3\Shop\Contracts;

use Lab3\Shop\Payment\PaymentResult;

interface Payable
{
    public function pay(float $amount): PaymentResult;
}
