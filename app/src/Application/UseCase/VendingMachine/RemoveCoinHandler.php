<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Model\VendingMachine\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;

class RemoveCoinHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(RemoveCoin $request): void
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $vendingMachine->getWallet()->removeCoin(
            new VendingMachineWalletCoin(
                $request->getMoney(),
                1
            )
        );

        $this->vendingMachineRepository->save($vendingMachine);
    }
}
