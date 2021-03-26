<?php

namespace App\Application\Presenter;

use App\Domain\Model\VendingMachineProduct;
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

        foreach ($vendingMachineProducts as $vendingMachineProduct) {
            $rows[] = [
                $vendingMachineProduct->getProduct()->getType()->getValue(),
                $vendingMachineProduct->getProduct()->getName(),
                $vendingMachineProduct->getProduct()->getPrice()->getValue(),
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
