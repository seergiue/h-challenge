<?php

namespace App\Domain\Model;

class VendingMachineWalletCoin
{
    private Coin $coin;

    private int $quantity;

    public function __construct(Coin $coin, $quantity)
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
}
