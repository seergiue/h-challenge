<?php

namespace App\Domain\Model;

use App\Domain\ValueObject\VendingMachineId;

class VendingMachine
{
    private VendingMachineId $id;

    /**
     * @var VendingMachineProduct[]
     */
    private array $products;

    private VendingMachineWallet $wallet;

    /**
     * @param VendingMachineProduct[] $products
     */
    public function __construct(array $products, VendingMachineWallet $wallet)
    {
        $this->id = VendingMachineId::fromRandom();
        $this->products = $products;
        $this->wallet = $wallet;
    }

    public function getId(): VendingMachineId
    {
        return $this->id;
    }

    /**
     * @return VendingMachineProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function getWallet(): VendingMachineWallet
    {
        return $this->wallet;
    }
}
