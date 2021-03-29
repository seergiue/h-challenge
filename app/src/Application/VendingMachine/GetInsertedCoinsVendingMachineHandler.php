<?php

namespace App\Application\VendingMachine;

use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;
use App\Domain\ValueObject\CoinType;
use App\Domain\ValueObject\Money;

class GetInsertedCoinsVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository) {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return Coin[]
     */
    public function execute(GetInsertedCoinsVendingMachine $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        return $vendingMachine->getWallet()->getInserted();
    }
}
