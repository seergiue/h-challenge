<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Exception\NotEnoughChangeVendingMachineException;
use App\Domain\Exception\NotEnoughMoneyVendingMachineException;
use App\Domain\Model\VendingMachine\VendingMachineProduct;
use App\Domain\Service\Repository\VendingMachineRepository;
use Money\Money;

class SelectProductHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return array<string, VendingMachineProduct|Money[]>
     * @throws NotEnoughMoneyVendingMachineException
     * @throws NotEnoughChangeVendingMachineException
     */
    public function execute(SelectProduct $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $result = $vendingMachine->buyProduct($request->getProductPosition());

        $this->vendingMachineRepository->save($vendingMachine);

        return $result;
    }
}
