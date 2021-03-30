<?php

namespace App\Domain\Exception;

use Exception;

class InvalidVendingMachineProductPositionException extends Exception
{
    public function __construct(int $position)
    {
        parent::__construct('A product at ' . $position . ' does not exist');
    }
}
