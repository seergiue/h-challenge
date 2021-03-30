<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class AddProduct
{
    private VendingMachineId $vendingMachineId;

    private int $position;

    private int $quantity;

    public function __construct(VendingMachineId $vendingMachineId, int $position, int $quantity)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->position = $position;
        $this->quantity = $quantity;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
