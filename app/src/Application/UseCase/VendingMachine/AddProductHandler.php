<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;

class AddProductHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(AddProduct $request): void
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $vendingMachine->addProduct($request->getPosition(), $request->getQuantity());

        $this->vendingMachineRepository->save($vendingMachine);
    }
}
