<?php

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Exception\NotEnoughMoneyVendingMachineException;
use App\Domain\Exception\ProductOutOfStockVendingMachineException;
use App\Domain\Model\Product;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Model\VendingMachine\VendingMachineProduct;
use App\Domain\Model\VendingMachine\VendingMachineWallet;
use App\Domain\Model\VendingMachine\VendingMachineWalletCoin;
use App\Domain\ValueObject\MoneyValue;
use App\Domain\ValueObject\ProductType;
use Money\Money;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    private const VALID_PRODUCT_POSITION = 0;

    private $vendingMachineMock;

    public function setUp(): void
    {
        $this->vendingMachineMock = $this->createMock(VendingMachine::class);
    }

    public function testItShouldBuyProductWithChange()
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::EUR(100), ProductType::water()), 1),
        ];

        $wallet = new VendingMachineWallet([]);
        $wallet->addCoins(
            [
                new VendingMachineWalletCoin(
                    MoneyValue::oneEuro(),
                    1
                ),
                new VendingMachineWalletCoin(
                    MoneyValue::fiveCents(),
                    1
                ),
            ]
        );

        $vendingMachine = new VendingMachine($products, $wallet);
        $result = $vendingMachine->buyProduct(self::VALID_PRODUCT_POSITION);

        self::assertEquals([MoneyValue::fiveCents()], $result['change']);
    }

    public function testItShouldBuyProductWithoutChange()
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::EUR(100), ProductType::water()), 1),
        ];

        $wallet = new VendingMachineWallet([]);
        $wallet->addCoin(
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        );

        $vendingMachine = new VendingMachine($products, $wallet);
        $result = $vendingMachine->buyProduct(self::VALID_PRODUCT_POSITION);

        self::assertEquals([], $result['change']);
    }

    public function testItShouldThrowProductOutOfStockVendingMachineException()
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::EUR(65), ProductType::water()), 0),
        ];

        $vendingMachine = new VendingMachine($products, new VendingMachineWallet([]));

        $this->expectException(ProductOutOfStockVendingMachineException::class);

        $vendingMachine->buyProduct(self::VALID_PRODUCT_POSITION);
    }

    public function testItShouldThrowNotEnoughMoneyVendingMachineException()
    {
        $vendingMachine = VendingMachine::withProductsAndWallet();

        $this->expectException(NotEnoughMoneyVendingMachineException::class);

        $vendingMachine->buyProduct(self::VALID_PRODUCT_POSITION);
    }

    public function testItShouldAddProduct()
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::EUR(65), ProductType::water()), 8),
        ];
        $vendingMachine = new VendingMachine($products, new VendingMachineWallet([]));

        $result = $vendingMachine->addProduct(
            self::VALID_PRODUCT_POSITION,
            1
        );

        self::assertEquals(9, $result->getQuantity());
    }

    public function testItShouldRemoveProduct()
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::EUR(65), ProductType::water()), 8),
        ];
        $vendingMachine = new VendingMachine($products, new VendingMachineWallet([]));

        $result = $vendingMachine->removeProduct(self::VALID_PRODUCT_POSITION);

        self::assertEquals(7, $result->getQuantity());
    }
}
