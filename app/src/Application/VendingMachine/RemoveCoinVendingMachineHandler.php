<?php

namespace App\Application\VendingMachine;

use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;
use App\Domain\ValueObject\CoinType;
use Money\Money;

class RemoveCoinVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository) {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(RemoveCoinVendingMachine $request): void
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
