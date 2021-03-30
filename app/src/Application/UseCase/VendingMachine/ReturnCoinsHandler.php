<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Service\Repository\VendingMachineRepository;
use Money\Money;

class ReturnCoinsHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return Money[]
     */
    public function execute(ReturnCoins $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        $this->vendingMachineRepository->save($vendingMachine);

        return $vendingMachine->getWallet()->returnCoins();
    }
}
