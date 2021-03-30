<?php

namespace App\Domain\Exception;

use Exception;

class NotEnoughMoneyVendingMachineException extends Exception
{
    public function __construct()
    {
        parent::__construct('There is no enough money to buy the product.');
    }
}
