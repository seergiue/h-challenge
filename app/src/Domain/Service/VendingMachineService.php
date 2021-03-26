<?php

namespace App\Domain\Service;

use Symfony\Component\Console\Output\OutputInterface;

interface VendingMachineService
{
    public function newMachine(): void;

    public function getVendingMachineProducts(OutputInterface $output): void;

    public function isInitialized(): bool;

    public function addCoin(float $coinValue, bool $serviceMode = false): void;

    public function removeCoin(float $coinValue, bool $serviceMode = false): void;

    public function getSummary(OutputInterface $output): void;
}
