<?php

namespace App\Application;

use App\Application\Presenter\VendingMachineProductsPresenter;
use App\Application\VendingMachine\CreateVendingMachineHandler;
use App\Application\VendingMachine\GetVendingMachineProducts;
use App\Application\VendingMachine\GetVendingMachineProductsHandler;
use App\Domain\Exception\VendingManagerNotInitializedException;
use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\VendingMachineId;
use Symfony\Component\Console\Output\OutputInterface;

class VendingMachineManager implements VendingMachineService
{
    private ?VendingMachineId $id = null;

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

    public function newMachine(): void
    {
        $vendingMachine = $this->createVendingMachineHandler->execute();
        $this->id = $vendingMachine->getId();
    }

    /**
     * @throws VendingManagerNotInitializedException
     */
    public function getvendingMachineProducts(OutputInterface $output): void
    {
        $this->assertIsInitialized();

        $request = new GetVendingMachineProducts($this->id);
        $products = $this->getVendingMachineProductsHandler->execute($request);

        $this->presenter->present($products, $output);
    }

    public function isInitialized(): bool
    {
        return null !== $this->id;
    }

    /**
     * @throws VendingManagerNotInitializedException
     */
    private function assertIsInitialized(): void
    {
        if (!$this->isInitialized()) {
            throw new VendingManagerNotInitializedException();
        }
    }
}
