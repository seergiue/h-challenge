<?php

namespace App\Application\VendingMachine;

use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;
use App\Domain\ValueObject\CoinType;
use App\Domain\ValueObject\Money;

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
                new Coin(
                    Money::fromValue(CoinType::fromValue($request->getType())->getValue()),
                    CoinType::fromValue($request->getType())
                ),
                1
            )
        );

        $this->vendingMachineRepository->save($vendingMachine);
    }
}