<?php

namespace App\Application\Presenter;

use App\Domain\Model\VendingMachineProduct;
use App\Domain\Model\VendingMachineWalletCoin;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
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
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        foreach ($summaryResults['products'] as $vendingMachineProduct) {
            $productRows[] = [
                $vendingMachineProduct->getProduct()->getType()->getValue(),
                $vendingMachineProduct->getProduct()->getName(),
                $moneyFormatter->format($vendingMachineProduct->getProduct()->getPrice()),
                $vendingMachineProduct->getQuantity()
            ];
        }

        foreach ($summaryResults['coins'] as $vendingMachineWalletCoin) {
            $currencies = new ISOCurrencies();
            $moneyFormatter = new DecimalMoneyFormatter($currencies);

            $coinRows[] = [
                $moneyFormatter->format($vendingMachineWalletCoin->getMoney()),
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
