<?php

namespace App\Domain\Exception;

use Exception;

class NotEnoughChangeVendingMachineException extends Exception
{
    public function __construct()
    {
        parent::__construct('There is no enough change at the moment. Buy another product.');
    }
}
