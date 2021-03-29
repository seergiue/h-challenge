<?php

namespace App\Domain\Service;

use App\Domain\Model\Coin;
use App\Domain\ValueObject\VendingMachineId;
use Symfony\Component\Console\Output\OutputInterface;

interface VendingMachineService
{
    public function newMachine(): void;

    public function getMachineId(): VendingMachineId;

    public function getVendingMachineProducts(OutputInterface $output): void;

    public function isInitialized(): bool;

    public function addCoin(float $coinValue, int $quantity = 1, bool $serviceMode = false): void;

    public function removeCoin(float $coinValue): void;

    public function getSummary(OutputInterface $output): void;

    public function getVendingMachineProductsInServiceMode(OutputInterface $output): void;

    public function addProduct(int $position, int $quantity): void;

    public function removeProduct(int $position): void;

    public function hasCoinsToReturn(): bool;

    /**
     * @return Coin[]
     */
    public function returnCoins(): array;

    /**
     * @return Coin[]
     */
    public function getInsertedCoins(): array;
}
