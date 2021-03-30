<?php

namespace App\Domain\Model\VendingMachine;

use App\Domain\Model\Product;

class VendingMachineProduct
{
    private Product $product;

    private int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        if ($quantity >= 0) {
            $this->quantity = $quantity;
        }

        return $this;
    }
}
