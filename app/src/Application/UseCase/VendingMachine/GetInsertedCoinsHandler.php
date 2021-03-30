<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;
use Money\Money;

class GetInsertedCoinsHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return Money[]
     */
    public function execute(GetInsertedCoins $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return $vendingMachine->getWallet()->getInserted();
    }
}
