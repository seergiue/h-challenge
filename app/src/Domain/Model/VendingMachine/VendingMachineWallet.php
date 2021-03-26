<?php

namespace App\Domain\Model;

class VendingMachineWallet
{
    /**
     * @var VendingMachineWalletCoin[]
     */
    private array $coins;

    /**
     * @var Coin[]
     */
    private array $inserted = [];

    /**
     * @param VendingMachineWalletCoin[] $vendingMachineWalletCoins
     */
    public function __construct(array $vendingMachineWalletCoins)
    {
        $this->coins = $vendingMachineWalletCoins;
    }

    /**
     * @return VendingMachineWalletCoin[]
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * @return Coin[]
     */
    public function getInserted(): array
    {
        return $this->inserted;
    }

    public function addCoin(VendingMachineWalletCoin $vendingMachineWalletCoin, bool $serviceMode = false): self
    {
        $index = $this->getCoinIndex($vendingMachineWalletCoin);

        if (null !== $index) {
            $this->coins[$index]->add();
        } else {
            $this->coins[] = $vendingMachineWalletCoin;
        }

        if (!$serviceMode) {
            $this->inserted[] = $vendingMachineWalletCoin->getCoin();
        }

        return $this;
    }

    /**
     * @param VendingMachineWalletCoin[] $vendingMachineWalletCoins
     */
    public function addCoins(array $vendingMachineWalletCoins): void
    {
        foreach ($vendingMachineWalletCoins as $vendingMachineWalletCoin) {
            $this->addCoin($vendingMachineWalletCoin);
        }
    }

    private function getCoinIndex(VendingMachineWalletCoin $vendingMachineWalletCoin): ?int
    {
        foreach ($this->coins as $index => $selfCoin) {
            if ($selfCoin->getCoin()->getType()->equals($vendingMachineWalletCoin->getCoin()->getType())) {
                return $index;
            }
        }

        return null;
    }
}
