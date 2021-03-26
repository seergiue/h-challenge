<?php

namespace App\Infrastructure\Repository;

use App\Domain\Exception\VendingMachineNotFoundException;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\Repository\VendingMachineRepository;
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

    /**
     * @throws VendingMachineNotFoundException
     */
    public function findById(VendingMachineId $vendingMachineId): VendingMachine
    {
        foreach ($this->vendingMachines as $vendingMachine) {
            if ($vendingMachine->getId()->equals($vendingMachineId)) {
                return $vendingMachine;
            }
        }

        throw new VendingMachineNotFoundException($vendingMachineId);
    }
}
