<?php

namespace App\Application\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class AddCoinVendingMachine
{
    private VendingMachineId $vendingMachineId;

    private float $type;

    private int $quantity;

    private bool $serviceMode;

    public function __construct(VendingMachineId $vendingMachineId, float $type, int $quantity, bool $serviceMode)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->serviceMode = $serviceMode;
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
    
    public function isServiceMode(): bool
    {
        return $this->serviceMode;
    }
}
