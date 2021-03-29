<?php

namespace App\Application\VendingMachine;

use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachineWalletCoin;
use App\Domain\Service\Repository\VendingMachineRepository;
use App\Domain\ValueObject\CoinType;
use App\Domain\ValueObject\Money;

class AddProductVendingMachineHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository) {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(AddProductVendingMachine $request): void
    {
        $vendingMachine = $this->vendingMachineRepository->findById($request->getVendingMachineId());
        $product = $vendingMachine->getProducts()[$request->getPosition()];
        $product->setQuantity($product->getQuantity() + $request->getQuantity());

        $this->vendingMachineRepository->save($vendingMachine);
    }
}
