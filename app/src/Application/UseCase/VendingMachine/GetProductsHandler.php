<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Model\VendingMachine\VendingMachineProduct;
use App\Domain\Service\Repository\VendingMachineRepository;

class GetProductsHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return VendingMachineProduct[]
     */
    public function execute(GetProducts $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return $vendingMachine->getProducts();
    }
}
