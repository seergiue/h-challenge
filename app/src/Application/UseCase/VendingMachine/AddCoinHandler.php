<?php

namespace App\Application\UseCase\VendingMachine;

use App\Domain\Exception\InvalidVendingMachineMoneyValueException;
use App\Domain\Model\VendingMachine\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;

class AddCoinHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @throws InvalidVendingMachineMoneyValueException
     */
    public function execute(AddCoin $request): void
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $vendingMachine->getWallet()->addCoin(
            new VendingMachineWalletCoin(
                $request->getMoney(),
                $request->getQuantity()
            ),
            $request->isServiceMode()
        );

        $this->vendingMachineRepository->save($vendingMachine);
    }
}
