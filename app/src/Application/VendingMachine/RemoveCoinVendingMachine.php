<?php

namespace App\Application\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class RemoveCoinVendingMachine
{
    private VendingMachineId $vendingMachineId;

    private float $type;

    public function __construct(VendingMachineId $vendingMachineId, float $type)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->type = $type;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getType(): float
    {
        return $this->type;
    }
}
