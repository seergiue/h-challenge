<?php

namespace App\Application\Presenter;

use App\Domain\Model\VendingMachineProduct;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class VendingMachineProductsInServiceModePresenter
{
    /**
     * @param VendingMachineProduct[] $vendingMachineProducts
     */
    public function present(array $vendingMachineProducts, OutputInterface $output): void
    {
        $rows = [];

        foreach ($vendingMachineProducts as $k => $vendingMachineProduct) {
            $rows[] = [
                $k,
                $vendingMachineProduct->getProduct()->getType()->getValue(),
                $vendingMachineProduct->getProduct()->getName(),
                $vendingMachineProduct->getProduct()->getPrice()->getValue(),
                $vendingMachineProduct->getQuantity()
            ];
        }
        $table = new Table($output);
        $table
            ->setHeaders(['Option', 'Type', 'Name', 'Price', 'Quantity'])
            ->setHeaderTitle('Products')
            ->setStyle('box')
            ->setRows($rows);
        $table->render();
    }
}
