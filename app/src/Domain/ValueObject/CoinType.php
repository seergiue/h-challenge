<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidCoinTypeException;

class CoinType
{
    private const TYPE_5_CENTS = 0.05;
    private const TYPE_10_CENTS = 0.10;
    private const TYPE_25_CENTS = 0.25;
    private const TYPE_1_EURO = 1.00;

    public const VALID_TYPES = [
        self::TYPE_5_CENTS,
        self::TYPE_10_CENTS,
        self::TYPE_25_CENTS,
        self::TYPE_1_EURO
    ];

    private float $type;

    private function __construct(float $type)
    {
        $this->assertIsValid($type);
        $this->type = $type;
    }

    public static function fromValue(float $type): self
    {
        return new self($type);
    }

    public static function fiveCents(): self
    {
        return new self(self::TYPE_5_CENTS);
    }

    public static function tenCents(): self
    {
        return new self(self::TYPE_10_CENTS);
    }

    public static function twentyFiveCents(): self
    {
        return new self(self::TYPE_25_CENTS);
    }

    public static function oneEuro(): self
    {
        return new self(self::TYPE_1_EURO);
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
        if (!in_array($type, self::VALID_TYPES)) {
            throw new InvalidCoinTypeException($type);
        }
    }
}
