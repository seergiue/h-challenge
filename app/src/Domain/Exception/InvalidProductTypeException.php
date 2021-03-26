<?php

namespace App\Domain\Exception;

use Exception;

class InvalidProductTypeException extends Exception
{
    public function __construct(string $type)
    {
        parent::__construct($type . ' is not a valid product type');
    }
}
