<?php

namespace App\Domain\Service;

use App\Domain\Model\VendingMachineProduct;
use Symfony\Component\Console\Output\OutputInterface;

interface VendingMachineService
{
    public function getManager(): self;

    public function newMachine(): void;

    /**
     * @return VendingMachineProduct[]
     */
    public function getvendingMachineProducts(OutputInterface $output): void;
}
