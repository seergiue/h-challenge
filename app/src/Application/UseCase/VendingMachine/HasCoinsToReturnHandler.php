<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;

class HasCoinsToReturnHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(HasCoinsToReturn $request): bool
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return $vendingMachine->getWallet()->hasCoinsToReturn();
    }
}
