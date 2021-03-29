<?php

namespace App\Application\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;

class RemoveProductVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository) {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(RemoveProductVendingMachine $request): void
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $product = $vendingMachine->getProducts()[$request->getPosition()];
        $product->setQuantity($product->getQuantity() - 1);

        $this->vendingMachineRepository->save($vendingMachine);
    }
}
