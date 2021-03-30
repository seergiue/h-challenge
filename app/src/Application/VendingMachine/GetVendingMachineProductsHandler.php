<?php

namespace App\Application\VendingMachine;

use App\Domain\Model\VendingMachineProduct;
use App\Domain\Service\Repository\VendingMachineRepository;

class GetVendingMachineProductsHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return VendingMachineProduct[]
     */
    public function execute(GetVendingMachineProducts $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return $vendingMachine->getProducts();
    }
}
