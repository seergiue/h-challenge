<?php

namespace App\Domain\Service;

use App\Domain\Model\VendingMachine;
use App\Domain\ValueObject\VendingMachineId;

interface VendingMachineRepository
{
    public function save(VendingMachine $vendingMachine): VendingMachine;

    public function existsById(VendingMachineId $vendingMachineId): bool;
}
