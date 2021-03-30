<?php

namespace App\Domain\Model;

use Money\Money;

class VendingMachineWalletCoin
{
    private Money $money;

    private int $quantity;

    public function __construct(Money $money, int $quantity)
    {
        $this->money = $money;
        $this->quantity = $quantity;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function add(int $quantity = 1): void
    {
        $this->quantity += $quantity;
    }

    public function remove(int $quantity = 1): void
    {
        if (($this->quantity - $quantity) >= 0) {
            $this->quantity -= $quantity;
        }
    }
}
