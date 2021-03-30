<?php

namespace App\Infrastructure\Repository;

use App\Domain\Exception\VendingMachineNotFoundException;
use App\Domain\Model\VendingMachine\VendingMachine;
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
        $selfKey = null;
        foreach ($this->vendingMachines as $key => $selfVendingMachine) {
            if ($vendingMachine->getId()->equals($vendingMachine->getId())) {
                $selfKey = $key;
                break;
            }
        }

        if (null !== $selfKey) {
            $this->vendingMachines[$selfKey] = $vendingMachine;
        } else {
            $this->vendingMachines[] = $vendingMachine;
        }

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
