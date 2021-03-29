<?php

namespace App\Application;

use App\Application\Presenter\VendingMachineProductsInServiceModePresenter;
use App\Application\Presenter\VendingMachineProductsPresenter;
use App\Application\Presenter\VendingMachineSummaryPresenter;
use App\Application\VendingMachine\AddCoinVendingMachine;
use App\Application\VendingMachine\AddCoinVendingMachineHandler;
use App\Application\VendingMachine\AddProductVendingMachine;
use App\Application\VendingMachine\AddProductVendingMachineHandler;
use App\Application\VendingMachine\CreateVendingMachineHandler;
use App\Application\VendingMachine\GetSummaryVendingMachine;
use App\Application\VendingMachine\GetSummaryVendingMachineHandler;
use App\Application\VendingMachine\GetVendingMachineProducts;
use App\Application\VendingMachine\GetVendingMachineProductsHandler;
use App\Application\VendingMachine\RemoveCoinVendingMachine;
use App\Application\VendingMachine\RemoveCoinVendingMachineHandler;
use App\Application\VendingMachine\RemoveProductVendingMachine;
use App\Application\VendingMachine\RemoveProductVendingMachineHandler;
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
    private RemoveCoinVendingMachineHandler $removeCoinVendingMachineHandler;
    private VendingMachineProductsInServiceModePresenter $vendingMachineProductsInServiceModePresenter;
    private AddProductVendingMachineHandler $addProductVendingMachineHandler;
    private RemoveProductVendingMachineHandler $removeProductVendingMachineHandler;

    public function __construct(
        CreateVendingMachineHandler $createVendingMachineHandler,
        GetVendingMachineProductsHandler $getVendingMachineProductsHandler,
        VendingMachineProductsPresenter $vendingMachineProductsPresenter,
        AddCoinVendingMachineHandler $addCoinVendingMachineHandler,
        VendingMachineSummaryPresenter $vendingMachineSummaryPresenter,
        GetSummaryVendingMachineHandler $getSummaryVendingMachineHandler,
        RemoveCoinVendingMachineHandler $removeCoinVendingMachineHandler,
        VendingMachineProductsInServiceModePresenter $vendingMachineProductsInServiceModePresenter,
        AddProductVendingMachineHandler $addProductVendingMachineHandler,
        RemoveProductVendingMachineHandler $removeProductVendingMachineHandler
    ) {
        $this->createVendingMachineHandler = $createVendingMachineHandler;
        $this->getVendingMachineProductsHandler = $getVendingMachineProductsHandler;
        $this->vendingMachineProductsPresenter = $vendingMachineProductsPresenter;
        $this->addCoinVendingMachineHandler = $addCoinVendingMachineHandler;
        $this->vendingMachineSummaryPresenter = $vendingMachineSummaryPresenter;
        $this->getSummaryVendingMachineHandler = $getSummaryVendingMachineHandler;
        $this->removeCoinVendingMachineHandler = $removeCoinVendingMachineHandler;
        $this->vendingMachineProductsInServiceModePresenter = $vendingMachineProductsInServiceModePresenter;
        $this->addProductVendingMachineHandler = $addProductVendingMachineHandler;
        $this->removeProductVendingMachineHandler = $removeProductVendingMachineHandler;
    }

    public function newMachine(): void
    {
        $vendingMachine = $this->createVendingMachineHandler->execute();
        $this->id = $vendingMachine->getId();
    }

    public function getMachineId(): VendingMachineId
    {
        return $this->id;
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

    public function addCoin(float $coinValue, int $quantity = 1, bool $serviceMode = false): void
    {
        $this->assertIsInitialized();

        $request = new AddCoinVendingMachine($this->id, $coinValue, $quantity);
        $this->addCoinVendingMachineHandler->execute($request);
    }

    public function removeCoin(float $coinValue, bool $serviceMode = false): void
    {
        $this->assertIsInitialized();

        $request = new RemoveCoinVendingMachine($this->id, $coinValue);
        $this->removeCoinVendingMachineHandler->execute($request);
    }

    public function getSummary(OutputInterface $output): void
    {
        $this->assertIsInitialized();

        $request = new GetSummaryVendingMachine($this->id);
        $result = $this->getSummaryVendingMachineHandler->execute($request);

        $this->vendingMachineSummaryPresenter->present($result, $output);
    }

    public function getVendingMachineProductsInServiceMode(OutputInterface $output): void
    {
        $this->assertIsInitialized();

        $request = new GetVendingMachineProducts($this->id);
        $products = $this->getVendingMachineProductsHandler->execute($request);

        $this->vendingMachineProductsInServiceModePresenter->present($products, $output);
    }

    public function addProduct(int $position, int $quantity): void
    {
        $this->assertIsInitialized();

        $request = new AddProductVendingMachine($this->id, $position, $quantity);
        $this->addProductVendingMachineHandler->execute($request);
    }

    public function removeProduct(int $position): void
    {
        $this->assertIsInitialized();

        $request = new RemoveProductVendingMachine($this->id, $position);
        $this->removeProductVendingMachineHandler->execute($request);
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
