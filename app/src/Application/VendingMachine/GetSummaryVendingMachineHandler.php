<?php

namespace App\Application\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;

class GetSummaryVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(GetSummaryVendingMachine $request)
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return [
            'products' => $vendingMachine->getProducts(),
            'coins' => $vendingMachine->getWallet()->getCoins()
        ];
    }
}
