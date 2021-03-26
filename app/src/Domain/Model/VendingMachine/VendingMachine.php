<?php

namespace App\Domain\Model;

use App\Domain\ValueObject\CoinType;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\ProductType;
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

    public static function withProductsAndWallet(): self
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::fromValue(0.65), ProductType::water()), 1),
            new VendingMachineProduct(new Product('Juice', Money::fromValue(1.00), ProductType::juice()), 1),
            new VendingMachineProduct(new Product('Soda', Money::fromValue(1.50), ProductType::soda()), 1),
        ];

        $coins = [
            new VendingMachineWalletCoin(new Coin(Money::fromValue(0.05), CoinType::fiveCents()), 10),
            new VendingMachineWalletCoin(new Coin(Money::fromValue(0.10), CoinType::tenCents()), 10),
            new VendingMachineWalletCoin(new Coin(Money::fromValue(0.25), CoinType::twentyFiveCents()), 10),
            new VendingMachineWalletCoin(new Coin(Money::fromValue(1.00), CoinType::oneEuro()), 10)
        ];

        $wallet = new VendingMachineWallet($coins);

        return new self($products, $wallet);
    }
}
