<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;

class RemoveProductHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(RemoveProduct $request): void
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $vendingMachine->removeProduct($request->getPosition());

        $this->vendingMachineRepository->save($vendingMachine);
    }
}
