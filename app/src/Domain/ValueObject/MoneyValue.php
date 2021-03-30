<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidVendingMachineMoneyValueException;
use Money\Money;

class MoneyValue
{
    private const TYPE_5_CENTS = 5;
    private const TYPE_10_CENTS = 10;
    private const TYPE_25_CENTS = 25;
    private const TYPE_1_EURO = 100;

    public const VALID_TYPES = [
        self::TYPE_5_CENTS,
        self::TYPE_10_CENTS,
        self::TYPE_25_CENTS,
        self::TYPE_1_EURO
    ];

    public static function fiveCents(): Money
    {
        return Money::EUR(self::TYPE_5_CENTS);
    }

    public static function tenCents(): Money
    {
        return Money::EUR(self::TYPE_10_CENTS);
    }

    public static function twentyFiveCents(): Money
    {
        return Money::EUR(self::TYPE_25_CENTS);
    }

    public static function oneEuro(): Money
    {
        return Money::EUR(self::TYPE_1_EURO);
    }

    /**
     * @return Money[]
     */
    public static function getAll(bool $sorted = false): array
    {
        $typesValues = self::VALID_TYPES;
        $typesObjects = [];

        if ($sorted) {
            sort($typesValues);
        }

        foreach ($typesValues as $value) {
            $typesObjects[] = Money::EUR($value);
        }

        return $typesObjects;
    }

    public static function assertIsValid(Money $money): void
    {
        if (!in_array($money->getAmount(), self::VALID_TYPES)) {
            throw new InvalidVendingMachineMoneyValueException($money);
        }
    }
}
