<?php

namespace App\Application\VendingMachine;

use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;
use App\Domain\ValueObject\CoinType;
use App\Domain\ValueObject\Money;

class ReturnCoinsVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository) {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return Coin[]
     */
    public function execute(ReturnCoinsVendingMachine $request): array
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());

        $this->vendingMachineRepository->save($vendingMachine);

        return $vendingMachine->getWallet()->returnCoins();
    }
}
