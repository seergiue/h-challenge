<?php

namespace App\Domain\ValueObject;

class VendingMachineId
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromRandom(): self
    {
        return new self(uniqid());
    }

    public static function fromValue(string $value): self
    {
        return new self($value);
    }

    public function equals(self $object): bool
    {
        return $this->value === $object->getValue();
    }
}
