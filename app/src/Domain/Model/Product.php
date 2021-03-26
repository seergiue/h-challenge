<?php

namespace App\Domain\Model;

use App\Domain\ValueObject\Money;

class Product
{
    private string $name;

    private Money $price;

    private ProductType $type;

    public function __construct(string $name, Money $money, ProductType $type)
    {
        $this->name = $name;
        $this->price = $money;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getType(): ProductType
    {
        return $this->type;
    }
}
