<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\VendingMachineId;
use Exception;

class VendingMachineNotFoundException extends Exception
{
    public function __construct(VendingMachineId $vendingMachineId)
    {
        parent::__construct('Vending Machine not found with id ' . $vendingMachineId->getValue());
    }
}
