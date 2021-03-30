<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class RemoveProduct
{
    private VendingMachineId $vendingMachineId;

    private int $position;

    public function __construct(VendingMachineId $vendingMachineId, int $position)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->position = $position;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
