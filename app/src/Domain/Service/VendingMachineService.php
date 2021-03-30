<?php

namespace App\Domain\Service;

use App\Domain\Model\VendingMachineProduct;
use App\Domain\ValueObject\VendingMachineId;
use Money\Money;
use Symfony\Component\Console\Output\OutputInterface;

interface VendingMachineService
{
    public function newMachine(): void;

    public function getMachineId(): VendingMachineId;

    public function displayVendingMachineProducts(OutputInterface $output, bool $asOptions = false): void;

    public function isInitialized(): bool;

    public function addCoin(Money $money, int $quantity = 1, bool $serviceMode = false): void;

    public function removeCoin(Money $money): void;

    public function getSummary(OutputInterface $output): void;

    public function addProduct(int $position, int $quantity): void;

    public function removeProduct(int $position): void;

    public function hasCoinsToReturn(): bool;

    /**
     * @return Money[]
     */
    public function returnCoins(): array;

    /**
     * @return Money[]
     */
    public function getInsertedCoins(): array;

    /**
     * @return array<string, VendingMachineProduct|Money[]>
     */
    public function buyProduct(int $position): array;
}
