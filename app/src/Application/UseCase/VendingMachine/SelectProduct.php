<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\ValueObject\VendingMachineId;

class SelectProduct
{
    private VendingMachineId $vendingMachineId;

    private int $productPosition;

    public function __construct(VendingMachineId $vendingMachineId, int $productPosition)
    {
        $this->vendingMachineId = $vendingMachineId;
        $this->productPosition = $productPosition;
    }

    public function getVendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function getProductPosition(): int
    {
        return $this->productPosition;
    }
}
