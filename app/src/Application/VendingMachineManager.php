<?php

namespace App\Application;

use App\Application\Presenter\VendingMachineProductsAsOptionsPresenter;
use App\Application\Presenter\VendingMachineProductsPresenter;
use App\Application\Presenter\VendingMachineSummaryPresenter;
use App\Application\UseCase\VendingMachine\AddCoin;
use App\Application\UseCase\VendingMachine\AddCoinHandler;
use App\Application\UseCase\VendingMachine\AddProduct;
use App\Application\UseCase\VendingMachine\AddProductHandler;
use App\Application\UseCase\VendingMachine\CreateVendingMachineHandler;
use App\Application\UseCase\VendingMachine\GetInsertedCoins;
use App\Application\UseCase\VendingMachine\GetInsertedCoinsHandler;
use App\Application\UseCase\VendingMachine\GetSummary;
use App\Application\UseCase\VendingMachine\GetSummaryHandler;
use App\Application\UseCase\VendingMachine\GetProducts;
use App\Application\UseCase\VendingMachine\GetProductsHandler;
use App\Application\UseCase\VendingMachine\HasCoinsToReturn;
use App\Application\UseCase\VendingMachine\HasCoinsToReturnHandler;
use App\Application\UseCase\VendingMachine\RemoveCoin;
use App\Application\UseCase\VendingMachine\RemoveCoinHandler;
use App\Application\UseCase\VendingMachine\RemoveProduct;
use App\Application\UseCase\VendingMachine\RemoveProductHandler;
use App\Application\UseCase\VendingMachine\ReturnCoins;
use App\Application\UseCase\VendingMachine\ReturnCoinsHandler;
use App\Application\UseCase\VendingMachine\SelectProduct;
use App\Application\UseCase\VendingMachine\SelectProductHandler;
use App\Domain\Exception\NotEnoughChangeVendingMachineException;
use App\Domain\Exception\NotEnoughMoneyVendingMachineException;
use App\Domain\Exception\VendingManagerNotInitializedException;
use App\Domain\Model\VendingMachine\VendingMachineProduct;
use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\VendingMachineId;
use Money\Money;
use Symfony\Component\Console\Output\OutputInterface;

class VendingMachineManager implements VendingMachineService
{
    private ?VendingMachineId $id = null;

    private CreateVendingMachineHandler $createVendingMachineHandler;
    private GetProductsHandler $getProductsHandler;
    private VendingMachineProductsPresenter $vendingMachineProductsPresenter;
    private AddCoinHandler $addCoinHandler;
    private VendingMachineSummaryPresenter $vendingMachineSummaryPresenter;
    private GetSummaryHandler $getSummaryHandler;
    private RemoveCoinHandler $removeCoinHandler;
    private AddProductHandler $addProductHandler;
    private RemoveProductHandler $removeProductHandler;
    private HasCoinsToReturnHandler $hasCoinsToReturnHandler;
    private ReturnCoinsHandler $returnCoinsHandler;
    private GetInsertedCoinsHandler $getInsertedCoinsHandler;
    private VendingMachineProductsAsOptionsPresenter $vendingMachineProductsAsOptionsPresenter;
    private SelectProductHandler $selectProductHandler;

    public function __construct(
        CreateVendingMachineHandler $createVendingMachineHandler,
        GetProductsHandler $getProductsHandler,
        VendingMachineProductsPresenter $vendingMachineProductsPresenter,
        AddCoinHandler $addCoinHandler,
        VendingMachineSummaryPresenter $vendingMachineSummaryPresenter,
        GetSummaryHandler $getSummaryHandler,
        RemoveCoinHandler $removeCoinHandler,
        AddProductHandler $addProductHandler,
        RemoveProductHandler $removeProductHandler,
        HasCoinsToReturnHandler $hasCoinsToReturnHandler,
        ReturnCoinsHandler $returnCoinsHandler,
        GetInsertedCoinsHandler $getInsertedCoinsHandler,
        VendingMachineProductsAsOptionsPresenter $vendingMachineProductsAsOptionsPresenter,
        SelectProductHandler $selectProductHandler
    ) {
        $this->createVendingMachineHandler = $createVendingMachineHandler;
        $this->getProductsHandler = $getProductsHandler;
        $this->vendingMachineProductsPresenter = $vendingMachineProductsPresenter;
        $this->addCoinHandler = $addCoinHandler;
        $this->vendingMachineSummaryPresenter = $vendingMachineSummaryPresenter;
        $this->getSummaryHandler = $getSummaryHandler;
        $this->removeCoinHandler = $removeCoinHandler;
        $this->addProductHandler = $addProductHandler;
        $this->removeProductHandler = $removeProductHandler;
        $this->hasCoinsToReturnHandler = $hasCoinsToReturnHandler;
        $this->returnCoinsHandler = $returnCoinsHandler;
        $this->getInsertedCoinsHandler = $getInsertedCoinsHandler;
        $this->vendingMachineProductsAsOptionsPresenter = $vendingMachineProductsAsOptionsPresenter;
        $this->selectProductHandler = $selectProductHandler;
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
    public function displayVendingMachineProducts(OutputInterface $output, bool $asOptions = false): void
    {
        $this->assertIsInitialized();

        $request = new GetProducts($this->id);
        $products = $this->getProductsHandler->execute($request);

        if ($asOptions) {
            $this->vendingMachineProductsAsOptionsPresenter->present($products, $output);
        } else {
            $this->vendingMachineProductsPresenter->present($products, $output);
        }
    }

    public function isInitialized(): bool
    {
        return null !== $this->id;
    }

    public function addCoin(Money $money, int $quantity = 1, bool $serviceMode = false): void
    {
        $this->assertIsInitialized();

        $request = new AddCoin($this->id, $money, $quantity, $serviceMode);
        $this->addCoinHandler->execute($request);
    }

    public function removeCoin(Money $money): void
    {
        $this->assertIsInitialized();

        $request = new RemoveCoin($this->id, $money);
        $this->removeCoinHandler->execute($request);
    }

    public function getSummary(OutputInterface $output): void
    {
        $this->assertIsInitialized();

        $request = new GetSummary($this->id);
        $result = $this->getSummaryHandler->execute($request);

        $this->vendingMachineSummaryPresenter->present($result, $output);
    }

    public function addProduct(int $position, int $quantity): void
    {
        $this->assertIsInitialized();

        $request = new AddProduct($this->id, $position, $quantity);
        $this->addProductHandler->execute($request);
    }

    public function removeProduct(int $position): void
    {
        $this->assertIsInitialized();

        $request = new RemoveProduct($this->id, $position);
        $this->removeProductHandler->execute($request);
    }

    public function hasCoinsToReturn(): bool
    {
        $this->assertIsInitialized();

        $request = new HasCoinsToReturn($this->id);
        return $this->hasCoinsToReturnHandler->execute($request);
    }

    /**
     * @return Money[]
     */
    public function returnCoins(): array
    {
        $this->assertIsInitialized();

        $request = new ReturnCoins($this->id);
        return $this->returnCoinsHandler->execute($request);
    }

    /**
     * @return Money[]
     */
    public function getInsertedCoins(): array
    {
        $this->assertIsInitialized();

        $request = new GetInsertedCoins($this->id);
        return $this->getInsertedCoinsHandler->execute($request);
    }

    /**
     * @return array<string, VendingMachineProduct|Money[]>
     * @throws NotEnoughChangeVendingMachineException
     * @throws NotEnoughMoneyVendingMachineException
     */
    public function buyProduct(int $position): array
    {
        $this->assertIsInitialized();

        $request = new SelectProduct($this->id, $position);
        return $this->selectProductHandler->execute($request);
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
