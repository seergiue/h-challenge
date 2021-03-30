<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Service\Repository\VendingMachineRepository;

class CreateVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(): VendingMachine
    {
        return $this->vendingMachineRepository->save(VendingMachine::withProductsAndWallet());
    }
}
