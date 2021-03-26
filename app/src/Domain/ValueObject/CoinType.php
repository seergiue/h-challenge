<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidCoinTypeException;

class CoinType
{
    private const AMOUNT_5_CENTS = 0.05;
    private const AMOUNT_10_CENTS = 0.10;
    private const AMOUNT_25_CENTS = 0.25;
    private const AMOUNT_1_EURO = 1.00;

    private const VALID_AMOUNTS = [
        self::AMOUNT_5_CENTS,
        self::AMOUNT_10_CENTS,
        self::AMOUNT_25_CENTS,
        self::AMOUNT_1_EURO
    ];

    private float $type;

    private function __construct(float $type)
    {
        $this->assertIsValid($type);
        $this->type = $type;
    }

    public static function fiveCents(): self
    {
        return new self(self::AMOUNT_5_CENTS);
    }

    public static function tenCents(): self
    {
        return new self(self::AMOUNT_10_CENTS);
    }

    public static function twentyFiveCents(): self
    {
        return new self(self::AMOUNT_25_CENTS);
    }

    public static function oneEuro(): self
    {
        return new self(self::AMOUNT_1_EURO);
    }

    public function getValue(): float
    {
        return $this->type;
    }

    public function equals(self $object): bool
    {
        return $this->type === $object->getValue();
    }

    /**
     * @throws InvalidCoinTypeException
     */
    private function assertIsValid(float $type): void
    {
        if (!in_array($type, self::VALID_AMOUNTS)) {
            throw new InvalidCoinTypeException($type);
        }
    }
}
