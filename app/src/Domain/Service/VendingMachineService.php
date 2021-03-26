<?php

namespace App\Domain\Service;

use Symfony\Component\Console\Output\OutputInterface;

interface VendingMachineService
{
    public function newMachine(): void;

    public function getvendingMachineProducts(OutputInterface $output): void;

    public function isInitialized(): bool;
}
