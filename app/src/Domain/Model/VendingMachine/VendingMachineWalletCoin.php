<?php

namespace App\Domain\Model;

class VendingMachineWalletCoin
{
    private Coin $coin;

    private int $quantity;

    public function __construct(Coin $coin, int $quantity)
    {
        $this->coin = $coin;
        $this->quantity = $quantity;
    }

    public function getCoin(): Coin
    {
        return $this->coin;
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
