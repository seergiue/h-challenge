<?php

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Exception\InvalidVendingMachineMoneyValueException;
use App\Domain\Model\Product;
use App\Domain\Model\VendingMachine\VendingMachineProduct;
use App\Domain\Model\VendingMachine\VendingMachineWallet;
use App\Domain\Model\VendingMachine\VendingMachineWalletCoin;
use App\Domain\ValueObject\MoneyValue;
use App\Domain\ValueObject\ProductType;
use Money\Money;
use PHPUnit\Framework\TestCase;

class VendingMachineWalletTest extends TestCase
{
    private const INVALID_MONEY_VALUE = 6;

    public function testItShouldHaveCoins()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];
        $vendingMachineWallet = new VendingMachineWallet($coins);
        $result = $vendingMachineWallet->getCoins();

        self::assertEquals($coins, $result);
    }

    public function testItShouldReturnInserted()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];
        $inserted = [
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                1
            ),
            new VendingMachineWalletCoin(
                MoneyValue::twentyFiveCents(),
                3
            )
        ];

        $expectedMoneyInserted = array_map(function(VendingMachineWalletCoin $coin) {
            return $coin->getMoney();
        }, $inserted);

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->addCoins($inserted);
        $result = $vendingMachineWallet->getInserted();

        self::assertEquals($expectedMoneyInserted, $result);
    }

    public function testItShouldThrowInvalidVendingMachineMoneyValueExceptionWhenAddingCoin()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $this->expectException(InvalidVendingMachineMoneyValueException::class);

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->addCoin(
            new VendingMachineWalletCoin(
                Money::EUR(self::INVALID_MONEY_VALUE),
                1
            )
        );
    }

    public function testItShouldThrowInvalidVendingMachineMoneyValueExceptionWhenRemovingCoin()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $this->expectException(InvalidVendingMachineMoneyValueException::class);

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->removeCoin(
            new VendingMachineWalletCoin(
                Money::EUR(self::INVALID_MONEY_VALUE),
                1
            )
        );
    }

    public function testItShouldAddNewCoin()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $expectedCoins = array_merge(
            $coins,
            [
                new VendingMachineWalletCoin(
                    MoneyValue::fiveCents(),
                    2
                )
            ]
        );

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->addCoin(
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                2
            )
        );

        self::assertEquals($expectedCoins, $vendingMachineWallet->getCoins());
    }

    public function testItShouldSumCoin()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $expectedCoins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                3
            )
        ];

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->addCoin(
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                2
            )
        );

        self::assertEquals($expectedCoins, $vendingMachineWallet->getCoins());
    }

    public function testItShouldSubtractCoin()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $expectedCoins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                0
            )
        ];

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->removeCoin(
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        );

        self::assertEquals($expectedCoins, $vendingMachineWallet->getCoins());
    }

    public function testItShouldReturnTrueCoinsToReturn()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->addCoin(
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                2
            )
        );

        self::assertEquals(true, $vendingMachineWallet->hasCoinsToReturn());
    }

    public function testItShouldReturnFalseCoinsToReturn()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];

        $vendingMachineWallet = new VendingMachineWallet($coins);

        self::assertEquals(false, $vendingMachineWallet->hasCoinsToReturn());
    }

    public function testItShouldReturnCoins()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            )
        ];
        $expectedCoins = [
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                1
            ),
            new VendingMachineWalletCoin(
                MoneyValue::twentyFiveCents(),
                1
            )
        ];
        $expectedCoinsAsMoney = array_map(function(VendingMachineWalletCoin $coin) {
            return $coin->getMoney();
        }, $expectedCoins);

        $vendingMachineWallet = new VendingMachineWallet($coins);
        $vendingMachineWallet->addCoins($expectedCoins);
        $result = $vendingMachineWallet->returnCoins();

        self::assertEquals($expectedCoinsAsMoney, $result);
        self::assertEquals(false, $vendingMachineWallet->hasCoinsToReturn());
    }

    public function testItShouldClearInsertedWhenCalculatingChange()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            ),
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                1
            ),
        ];

        $product = new Product('Water', Money::EUR(100), ProductType::water());

        $vendingMachineWallet = new VendingMachineWallet([]);
        $vendingMachineWallet->addCoins($coins);

        $vendingMachineWallet->calculateChange($product);

        self::assertEquals([], $vendingMachineWallet->getInserted());
    }

    public function testItShouldReturnChange()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::oneEuro(),
                1
            ),
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                1
            ),
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                1
            ),
        ];

        $product = new Product('Water', Money::EUR(100), ProductType::water());

        $vendingMachineWallet = new VendingMachineWallet([]);
        $vendingMachineWallet->addCoins($coins);

        $result = $vendingMachineWallet->calculateChange($product);

        self::assertEquals([MoneyValue::fiveCents(), MoneyValue::fiveCents()], $result);
    }

    public function testItShouldNotReturnChange()
    {
        $coins = [
            new VendingMachineWalletCoin(
                MoneyValue::fiveCents(),
                1
            )
        ];

        $product = new Product('Water', MoneyValue::fiveCents(), ProductType::water());

        $vendingMachineWallet = new VendingMachineWallet([]);
        $vendingMachineWallet->addCoins($coins);

        $result = $vendingMachineWallet->calculateChange($product);

        self::assertEquals([], $result);
    }
}
