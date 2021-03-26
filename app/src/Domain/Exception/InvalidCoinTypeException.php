<?php

namespace App\Domain\Exception;

use Exception;

class InvalidCoinTypeException extends Exception
{
    public function __construct(float $type)
    {
        parent::__construct($type . ' is not a valid coin type');
    }
}
