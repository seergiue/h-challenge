<?php

namespace App\Application\Presenter;

use App\Domain\Model\VendingMachineProduct;
use App\Domain\Model\VendingMachineWalletCoin;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class VendingMachineSummaryPresenter
{
    /**
     * @param array<string, VendingMachineProduct[]|VendingMachineWalletCoin[]> $summaryResults
     */
    public function present(array $summaryResults, OutputInterface $output): void
    {
        $productRows = [];
        $coinRows = [];

        foreach ($summaryResults['products'] as $vendingMachineProduct) {
            $productRows[] = [
                $vendingMachineProduct->getProduct()->getType()->getValue(),
                $vendingMachineProduct->getProduct()->getName(),
                $vendingMachineProduct->getProduct()->getPrice()->getValue(),
                $vendingMachineProduct->getQuantity()
            ];
        }

        foreach ($summaryResults['coins'] as $vendingMachineWalletCoin) {
            $coinRows[] = [
                $vendingMachineWalletCoin->getCoin()->getAmount()->getValue(),
                $vendingMachineWalletCoin->getQuantity()
            ];
        }

        $productsTable = new Table($output);
        $productsTable
            ->setHeaders(['Type', 'Name', 'Price', 'Quantity'])
            ->setHeaderTitle('Products')
            ->setStyle('box')
            ->setRows($productRows);
        $productsTable->render();

        $coinsTable = new Table($output);
        $coinsTable
            ->setHeaders(['Value', 'Quantity'])
            ->setHeaderTitle('Coins')
            ->setStyle('box')
            ->setRows($coinRows);
        $coinsTable->render();
    }
}
