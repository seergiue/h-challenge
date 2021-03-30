<?php

namespace App\Domain\Model\VendingMachine;

use App\Domain\Exception\InvalidVendingMachineMoneyValueException;
use App\Domain\Exception\NotEnoughChangeVendingMachineException;
use App\Domain\Model\Product;
use App\Domain\ValueObject\MoneyValue;
use Money\Money;

class VendingMachineWallet
{
    /**
     * @var VendingMachineWalletCoin[]
     */
    private array $coins;

    /**
     * @var Money[]
     */
    private array $inserted = [];

    /**
     * @param VendingMachineWalletCoin[] $vendingMachineWalletCoins
     */
    public function __construct(array $vendingMachineWalletCoins)
    {
        $this->coins = $vendingMachineWalletCoins;
    }

    /**
     * @return VendingMachineWalletCoin[]
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * @return Money[]
     */
    public function getInserted(): array
    {
        return $this->inserted;
    }

    /**
     * @throws InvalidVendingMachineMoneyValueException
     */
    public function addCoin(VendingMachineWalletCoin $vendingMachineWalletCoin, bool $serviceMode = false): self
    {
        MoneyValue::assertIsValid($vendingMachineWalletCoin->getMoney());

        $index = $this->getCoinIndex($vendingMachineWalletCoin);

        if (null !== $index) {
            $this->coins[$index]->add($vendingMachineWalletCoin->getQuantity());
        } else {
            $this->coins[] = $vendingMachineWalletCoin;
        }

        if (!$serviceMode) {
            $this->inserted[] = $vendingMachineWalletCoin->getMoney();
        }

        return $this;
    }

    public function removeCoin(VendingMachineWalletCoin $vendingMachineWalletCoin): self
    {
        $index = $this->getCoinIndex($vendingMachineWalletCoin);

        if (null !== $index) {
            $this->coins[$index]->remove();
        }

        return $this;
    }

    /**
     * @param VendingMachineWalletCoin[] $vendingMachineWalletCoins
     */
    public function addCoins(array $vendingMachineWalletCoins): void
    {
        foreach ($vendingMachineWalletCoins as $vendingMachineWalletCoin) {
            $this->addCoin($vendingMachineWalletCoin);
        }
    }

    public function hasCoinsToReturn(): bool
    {
        return !empty($this->inserted);
    }

    /**
     * @return Money[]
     */
    public function returnCoins(): array
    {
        $insertedCoins = $this->inserted;

        foreach ($insertedCoins as $insertedCoin) {
            $this->removeCoin(new VendingMachineWalletCoin($insertedCoin, 1));
        }
        $this->inserted = [];

        return $insertedCoins;
    }

    public function canBuy(Product $product): bool
    {
        return $this->getInsertedAmount()->greaterThanOrEqual($product->getPrice());
    }

    private function getInsertedAmount(): Money
    {
        $insertedValues = array_map(function (Money $money) {
            return $money->getAmount();
        }, $this->inserted);

        $sum = array_sum($insertedValues);

        return Money::EUR($sum);
    }

    /**
     * @return Money[]
     * @throws NotEnoughChangeVendingMachineException
     */
    public function calculateChange(Product $product): array
    {
        $change = $this->getInsertedAmount()->subtract($product->getPrice());
        $originalChange = $change;

        if ($change->greaterThan(Money::EUR(0))) {
            /** @var Money[] $coinsToReturn */
            $coinsToReturn = [];
            $coins = $this->coins;
            usort($coins, function (VendingMachineWalletCoin $a, VendingMachineWalletCoin $b) {
                return strcmp($a->getMoney()->getAmount(), $b->getMoney()->getAmount());
            });
            $returnSum = Money::EUR(0);

            foreach ($coins as $coin) {
                $quantity = (int)($change->getAmount() / $coin->getMoney()->getAmount());

                if ($quantity > 0 && ($coin->getQuantity() - $quantity > 0)) {
                    for ($i = 0; $i < $quantity; $i++) {
                        $coinsToReturn[] = $coin->getMoney();
                        $this->removeCoin($coin);
                        $returnSum = $returnSum->add($coin->getMoney());
                    }
                    $toSubtract = $coin->getMoney()->multiply($quantity);
                    $change = $change->subtract($toSubtract);
                }
            }

            if (!$returnSum->equals($originalChange)) {
                throw new NotEnoughChangeVendingMachineException();
            }

            $this->inserted = [];

            return $coinsToReturn;
        }

        $this->inserted = [];

        return $this->returnCoins();
    }

    private function getCoinIndex(VendingMachineWalletCoin $vendingMachineWalletCoin): ?int
    {
        foreach ($this->coins as $index => $selfCoin) {
            if ($selfCoin->getMoney()->equals($vendingMachineWalletCoin->getMoney())) {
                return $index;
            }
        }

        return null;
    }
}
