<?php

namespace App\Domain\Model;

use App\Domain\ValueObject\Money;

class Coin
{
    private Money $amount;

    public function __construct(Money $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }
}
