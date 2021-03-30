<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class GetInsertedCoins
{
    private VendingMachineId $vendingMachineId;

    public function __construct(VendingMachineId $vendingMachineId)
    {
        $this->vendingMachineId = $vendingMachineId;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }
}
