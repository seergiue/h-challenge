<?php

namespace App\Domain\Model;

class VendingMachineWallet
{
    /**
     * @var VendingMachineWalletCoin[]
     */
    private array $coins;

    /**
     * @param VendingMachineWalletCoin[] $coins
     */
    public function __construct(array $coins)
    {
        $this->coins = $coins;
    }

    /**
     * @return VendingMachineWalletCoin[]
     */
    public function getCoins(): array
    {
        return $this->coins;
    }
}
