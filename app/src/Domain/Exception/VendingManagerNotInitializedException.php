<?php

namespace App\Domain\Exception;

use Exception;

class VendingManagerNotInitializedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Run vending-machine:start');
    }
}
