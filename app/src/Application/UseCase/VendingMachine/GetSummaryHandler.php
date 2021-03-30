<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;

class GetSummaryHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(GetSummary $request)
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return [
            'products' => $vendingMachine->getProducts(),
            'coins' => $vendingMachine->getWallet()->getCoins()
        ];
    }
}
