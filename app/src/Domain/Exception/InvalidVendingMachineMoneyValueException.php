<?php

namespace App\Domain\Exception;

use Exception;
use Money\Money;

class InvalidVendingMachineMoneyValueException extends Exception
{
    public function __construct(Money $money)
    {
        parent::__construct('Invalid money value: ' . $money->getAmount());
    }
}
