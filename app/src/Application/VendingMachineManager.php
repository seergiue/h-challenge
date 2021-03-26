<?php

namespace App\Application;

use App\Application\Presenter\VendingMachineProductsPresenter;
use App\Application\VendingMachine\CreateVendingMachineHandler;
use App\Application\VendingMachine\GetVendingMachineProducts;
use App\Application\VendingMachine\GetVendingMachineProductsHandler;
use App\Domain\Model\VendingMachineProduct;
use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\VendingMachineId;
use Symfony\Component\Console\Output\OutputInterface;

class VendingMachineManager implements VendingMachineService
{
    private VendingMachineId $id;

    private CreateVendingMachineHandler $createVendingMachineHandler;
    private GetVendingMachineProductsHandler $getVendingMachineProductsHandler;
    private VendingMachineProductsPresenter $presenter;

    public function __construct(
        CreateVendingMachineHandler $createVendingMachineHandler,
        GetVendingMachineProductsHandler $getVendingMachineProductsHandler,
        VendingMachineProductsPresenter $presenter
    ) {
        $this->createVendingMachineHandler = $createVendingMachineHandler;
        $this->getVendingMachineProductsHandler = $getVendingMachineProductsHandler;
        $this->presenter = $presenter;
    }

    public function getManager(): self
    {
        return $this;
    }

    public function newMachine(): void
    {
        $vendingMachine = $this->createVendingMachineHandler->execute();
        $this->id = $vendingMachine->getId();
    }

    /**
     * @return VendingMachineProduct[]
     */
    public function getvendingMachineProducts(OutputInterface $output): void
    {
        $request = new GetVendingMachineProducts($this->id);
        $products = $this->getVendingMachineProductsHandler->execute($request);

        $this->presenter->present($products, $output);
    }
}
