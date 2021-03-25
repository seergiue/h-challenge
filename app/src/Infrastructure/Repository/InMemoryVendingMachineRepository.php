<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use App\Domain\ValueObject\VendingMachineId;

class InMemoryVendingMachineRepository implements VendingMachineRepository
{
    /**
     * @var VendingMachine[]
     */
    private array $vendingMachines = [];

    public function save(VendingMachine $vendingMachine): VendingMachine
    {
        $this->vendingMachines[] = $vendingMachine;

        return $vendingMachine;
    }

    public function existsById(VendingMachineId $vendingMachineId): bool
    {
        return in_array($vendingMachineId->getValue(), array_column($this->vendingMachines, 'id'), true);
    }
}
