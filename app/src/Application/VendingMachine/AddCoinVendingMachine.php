<?php

namespace App\Application\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;
use Money\Money;

class AddCoinVendingMachine
{
    private VendingMachineId $vendingMachineId;

    private Money $money;

    private int $quantity;

    private bool $serviceMode;

    public function __construct(VendingMachineId $vendingMachineId, Money $money, int $quantity, bool $serviceMode)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->money = $money;
        $this->quantity = $quantity;
        $this->serviceMode = $serviceMode;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function isServiceMode(): bool
    {
        return $this->serviceMode;
    }
}
