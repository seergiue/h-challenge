<?php

namespace App\Domain\Model;

use App\Domain\ValueObject\CoinType;
use App\Domain\ValueObject\Money;

class Coin
{
    private Money $amount;

    private CoinType $type;

    public function __construct(Money $amount, CoinType $type)
    {
        $this->amount = $amount;
        $this->type = $type;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getType(): CoinType
    {
        return $this->type;
    }
}
