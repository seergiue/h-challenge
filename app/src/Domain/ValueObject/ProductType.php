<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidProductTypeException;

class ProductType
{
    private const WATER = 'water';
    private const JUICE = 'juice';
    private const SODA = 'soda';

    private const TYPES = [
        self::WATER,
        self::JUICE,
        self::SODA
    ];

    private string $type;

    /**
     * @throws InvalidProductTypeException
     */
    private function __construct(string $type)
    {
        $this->assertIsValid($type);
        $this->type = $type;
    }

    public static function water(): self
    {
        return new self(self::WATER);
    }

    public static function juice(): self
    {
        return new self(self::JUICE);
    }

    public static function soda(): self
    {
        return new self(self::SODA);
    }

    public function getValue(): string
    {
        return $this->type;
    }

    /**
     * @throws InvalidProductTypeException
     */
    private function assertIsValid(string $type): void
    {
        if (!in_array($type, self::TYPES)) {
            throw new InvalidProductTypeException($type);
        }
    }
}
