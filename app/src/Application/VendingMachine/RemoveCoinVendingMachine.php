<?php

namespace App\Application\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;
use Money\Money;

class RemoveCoinVendingMachine
{
    private VendingMachineId $vendingMachineId;

    private Money $money;

    public function __construct(VendingMachineId $vendingMachineId, Money $money)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->money = $money;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }
}
