<?php

namespace App\Application;

use App\Application\Presenter\VendingMachineProductsPresenter;
use App\Application\Presenter\VendingMachineSummaryPresenter;
use App\Application\VendingMachine\AddCoinVendingMachine;
use App\Application\VendingMachine\AddCoinVendingMachineHandler;
use App\Application\VendingMachine\CreateVendingMachineHandler;
use App\Application\VendingMachine\GetSummaryVendingMachine;
use App\Application\VendingMachine\GetSummaryVendingMachineHandler;
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
    private VendingMachineProductsPresenter $vendingMachineProductsPresenter;
    private AddCoinVendingMachineHandler $addCoinVendingMachineHandler;
    private VendingMachineSummaryPresenter $vendingMachineSummaryPresenter;
    private GetSummaryVendingMachineHandler $getSummaryVendingMachineHandler;

    public function __construct(
        CreateVendingMachineHandler $createVendingMachineHandler,
        GetVendingMachineProductsHandler $getVendingMachineProductsHandler,
        VendingMachineProductsPresenter $vendingMachineProductsPresenter,
        AddCoinVendingMachineHandler $addCoinVendingMachineHandler,
        VendingMachineSummaryPresenter $vendingMachineSummaryPresenter,
        GetSummaryVendingMachineHandler $getSummaryVendingMachineHandler
    ) {
        $this->createVendingMachineHandler = $createVendingMachineHandler;
        $this->getVendingMachineProductsHandler = $getVendingMachineProductsHandler;
        $this->vendingMachineProductsPresenter = $vendingMachineProductsPresenter;
        $this->addCoinVendingMachineHandler = $addCoinVendingMachineHandler;
        $this->vendingMachineSummaryPresenter = $vendingMachineSummaryPresenter;
        $this->getSummaryVendingMachineHandler = $getSummaryVendingMachineHandler;
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

        $this->vendingMachineProductsPresenter->present($products, $output);
    }

    public function isInitialized(): bool
    {
        return null !== $this->id;
    }

    public function addCoin(float $coinValue, bool $serviceMode = false): void
    {
        $this->assertIsInitialized();

        $request = new AddCoinVendingMachine($this->id, $coinValue);
        $this->addCoinVendingMachineHandler->execute($request);
    }

    public function getSummary(OutputInterface $output): void
    {
        $this->assertIsInitialized();

        $request = new GetSummaryVendingMachine($this->id);
        $result = $this->getSummaryVendingMachineHandler->execute($request);

        $this->vendingMachineSummaryPresenter->present($result, $output);
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
