<?php

namespace App\Domain\Exception;

use App\Domain\Model\Product;
use Exception;

class ProductOutOfStockVendingMachineException extends Exception
{
    public function __construct(Product $product)
    {
        parent::__construct('The product (' . $product->getName() . ') ran out of stock.');
    }
}
