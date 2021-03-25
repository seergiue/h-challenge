<?php

namespace App\Domain\Model;

use App\Domain\ValueObject\Money;

class Product
{
    private string $name;

    private Money $price;

    public function __construct(string $name, Money $money)
    {
        $this->name = $name;
        $this->price = $money;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}
