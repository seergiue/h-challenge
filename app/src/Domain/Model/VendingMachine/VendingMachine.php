<?php

namespace App\Domain\Model\VendingMachine;

use App\Domain\Exception\InvalidVendingMachineProductPositionException;
use App\Domain\Exception\NotEnoughChangeVendingMachineException;
use App\Domain\Exception\NotEnoughMoneyVendingMachineException;
use App\Domain\Exception\ProductOutOfStockVendingMachineException;
use App\Domain\Model\Product;
use App\Domain\ValueObject\MoneyValue;
use Money\Money;
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
            new VendingMachineProduct(new Product('Water', Money::EUR(65), ProductType::water()), 1),
            new VendingMachineProduct(new Product('Juice', Money::EUR(100), ProductType::juice()), 1),
            new VendingMachineProduct(new Product('Soda', Money::EUR(150), ProductType::soda()), 1),
        ];

        $coins = [
            new VendingMachineWalletCoin(MoneyValue::fiveCents(), 10),
            new VendingMachineWalletCoin(MoneyValue::tenCents(), 10),
            new VendingMachineWalletCoin(MoneyValue::twentyFiveCents(), 10),
            new VendingMachineWalletCoin(MoneyValue::oneEuro(), 10),
        ];

        $wallet = new VendingMachineWallet($coins);

        return new self($products, $wallet);
    }

    /**
     * @return array<string, VendingMachineProduct|Money[]>
     * @throws NotEnoughMoneyVendingMachineException
     * @throws NotEnoughChangeVendingMachineException
     */
    public function buyProduct(int $position): array
    {
        $vendingMachineProduct = $this->getProductAt($position);
        $wallet = $this->getWallet();

        if ($vendingMachineProduct->getQuantity() <= 0) {
            throw new ProductOutOfStockVendingMachineException($vendingMachineProduct->getProduct());
        }

        if (!$wallet->canBuy($vendingMachineProduct->getProduct())) {
            throw new NotEnoughMoneyVendingMachineException();
        }

        $change = $this->getWallet()->calculateChange($vendingMachineProduct->getProduct());
        $this->removeProduct($position);

        return [
            'product' => $vendingMachineProduct,
            'change' => $change
        ];
    }

    /**
     * @throws InvalidVendingMachineProductPositionException
     */
    public function addProduct(int $position, int $quantity = 1): VendingMachineProduct
    {
        $vendingMachineProduct = $this->getProductAt($position);
        $vendingMachineProduct->setQuantity($vendingMachineProduct->getQuantity() + $quantity);

        return $vendingMachineProduct;
    }

    /**
     * @throws InvalidVendingMachineProductPositionException
     */
    public function removeProduct(int $position): VendingMachineProduct
    {
        $vendingMachineProduct = $this->getProductAt($position);
        $vendingMachineProduct->setQuantity($vendingMachineProduct->getQuantity() - 1);

        return $vendingMachineProduct;
    }

    /**
     * @throws InvalidVendingMachineProductPositionException
     */
    private function getProductAt(int $position): VendingMachineProduct
    {
        $products = $this->getProducts();
        if (!array_key_exists($position, $products)) {
            throw new InvalidVendingMachineProductPositionException($position);
        }

        return $products[$position];
    }
}
