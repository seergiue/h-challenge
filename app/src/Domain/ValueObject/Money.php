<?php

namespace App\Domain\ValueObject;

class Money
{
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public static function fromValue(float $value): self
    {
        return new self($value);
    }

    public function equals(self $object): bool
    {
        return $this->value === $object->getValue();
    }
}
