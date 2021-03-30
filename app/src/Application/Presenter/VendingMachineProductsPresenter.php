<?php

namespace App\Application\Presenter;

use App\Domain\Model\VendingMachine\VendingMachineProduct;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class VendingMachineProductsPresenter
{
    /**
     * @param VendingMachineProduct[] $vendingMachineProducts
     */
    public function present(array $vendingMachineProducts, OutputInterface $output): void
    {
        $rows = [];
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        foreach ($vendingMachineProducts as $vendingMachineProduct) {

            $rows[] = [
                $vendingMachineProduct->getProduct()->getType()->getValue(),
                $vendingMachineProduct->getProduct()->getName(),
                $moneyFormatter->format($vendingMachineProduct->getProduct()->getPrice()),
                $vendingMachineProduct->getQuantity()
            ];
        }
        $table = new Table($output);
        $table
            ->setHeaders(['Type', 'Name', 'Price', 'Quantity'])
            ->setHeaderTitle('Products')
            ->setStyle('box')
            ->setRows($rows);
        $table->render();
    }
}
