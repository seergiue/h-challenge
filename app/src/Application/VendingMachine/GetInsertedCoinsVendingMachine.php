<?php

namespace App\Application\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class GetInsertedCoinsVendingMachine
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