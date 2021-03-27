<?php

namespace App\Application\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class AddCoinVendingMachine
{
    private VendingMachineId $vendingMachineId;

    private float $type;

    private int $quantity;

    public function __construct(VendingMachineId $vendingMachineId, float $type, int $quantity)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->type = $type;
        $this->quantity = $quantity;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getType(): float
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
