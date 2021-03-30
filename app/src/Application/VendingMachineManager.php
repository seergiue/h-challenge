<?php

namespace App\Application;

use App\Application\Presenter\VendingMachineProductsAsOptionsPresenter;
use App\Application\Presenter\VendingMachineProductsInServiceModePresenter;
use App\Application\Presenter\VendingMachineProductsPresenter;
use App\Application\Presenter\VendingMachineSummaryPresenter;
use App\Application\VendingMachine\AddCoinVendingMachine;
use App\Application\VendingMachine\AddCoinVendingMachineHandler;
use App\Application\VendingMachine\AddProductVendingMachine;
use App\Application\VendingMachine\AddProductVendingMachineHandler;
use App\Application\VendingMachine\CreateVendingMachineHandler;
use App\Application\VendingMachine\GetInsertedCoinsVendingMachine;
use App\Application\VendingMachine\GetInsertedCoinsVendingMachineHandler;
use App\Application\VendingMachine\GetSummaryVendingMachine;
use App\Application\VendingMachine\GetSummaryVendingMachineHandler;
use App\Application\VendingMachine\GetVendingMachineProducts;
use App\Application\VendingMachine\GetVendingMachineProductsHandler;
use App\Application\VendingMachine\HasCoinsToReturnVendingMachine;
use App\Application\VendingMachine\HasCoinsToReturnVendingMachineHandler;
use App\Application\VendingMachine\RemoveCoinVendingMachine;
use App\Application\VendingMachine\RemoveCoinVendingMachineHandler;
use App\Application\VendingMachine\RemoveProductVendingMachine;
use App\Application\VendingMachine\RemoveProductVendingMachineHandler;
use App\Application\VendingMachine\ReturnCoinsVendingMachine;
use App\Application\VendingMachine\ReturnCoinsVendingMachineHandler;
use App\Application\VendingMachine\SelectProductVendingMachine;
use App\Application\VendingMachine\SelectProductVendingMachineHandler;
use App\Domain\Exception\NotEnoughChangeVendingMachineException;
use App\Domain\Exception\NotEnoughMoneyVendingMachineException;
use App\Domain\Exception\VendingManagerNotInitializedException;
use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachineProduct;
use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\VendingMachineId;
use Money\Money;
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
    private AddProductVendingMachineHandler $addProductVendingMachineHandler;
    private RemoveProductVendingMachineHandler $removeProductVendingMachineHandler;
    private HasCoinsToReturnVendingMachineHandler $hasCoinsToReturnVendingMachineHandler;
    private ReturnCoinsVendingMachineHandler $returnCoinsVendingMachineHandler;
    private GetInsertedCoinsVendingMachineHandler $getInsertedCoinsVendingMachineHandler;
    private VendingMachineProductsAsOptionsPresenter $vendingMachineProductsAsOptionsPresenter;
    private SelectProductVendingMachineHandler $selectProductVendingMachineHandler;

    public function __construct(
        CreateVendingMachineHandler $createVendingMachineHandler,
        GetVendingMachineProductsHandler $getVendingMachineProductsHandler,
        VendingMachineProductsPresenter $vendingMachineProductsPresenter,
        AddCoinVendingMachineHandler $addCoinVendingMachineHandler,
        VendingMachineSummaryPresenter $vendingMachineSummaryPresenter,
        GetSummaryVendingMachineHandler $getSummaryVendingMachineHandler,
        RemoveCoinVendingMachineHandler $removeCoinVendingMachineHandler,
        AddProductVendingMachineHandler $addProductVendingMachineHandler,
        RemoveProductVendingMachineHandler $removeProductVendingMachineHandler,
        HasCoinsToReturnVendingMachineHandler $hasCoinsToReturnVendingMachineHandler,
        ReturnCoinsVendingMachineHandler $returnCoinsVendingMachineHandler,
        GetInsertedCoinsVendingMachineHandler $getInsertedCoinsVendingMachineHandler,
        VendingMachineProductsAsOptionsPresenter $vendingMachineProductsAsOptionsPresenter,
        SelectProductVendingMachineHandler $selectProductVendingMachineHandler
    ) {
        $this->createVendingMachineHandler = $createVendingMachineHandler;
        $this->getVendingMachineProductsHandler = $getVendingMachineProductsHandler;
        $this->vendingMachineProductsPresenter = $vendingMachineProductsPresenter;
        $this->addCoinVendingMachineHandler = $addCoinVendingMachineHandler;
        $this->vendingMachineSummaryPresenter = $vendingMachineSummaryPresenter;
        $this->getSummaryVendingMachineHandler = $getSummaryVendingMachineHandler;
        $this->removeCoinVendingMachineHandler = $removeCoinVendingMachineHandler;
        $this->addProductVendingMachineHandler = $addProductVendingMachineHandler;
        $this->removeProductVendingMachineHandler = $removeProductVendingMachineHandler;
        $this->hasCoinsToReturnVendingMachineHandler = $hasCoinsToReturnVendingMachineHandler;
        $this->returnCoinsVendingMachineHandler = $returnCoinsVendingMachineHandler;
        $this->getInsertedCoinsVendingMachineHandler = $getInsertedCoinsVendingMachineHandler;
        $this->vendingMachineProductsAsOptionsPresenter = $vendingMachineProductsAsOptionsPresenter;
        $this->selectProductVendingMachineHandler = $selectProductVendingMachineHandler;
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

        $request = new GetVendingMachineProducts($this->id);
        $products = $this->getVendingMachineProductsHandler->execute($request);

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

        $request = new AddCoinVendingMachine($this->id, $money, $quantity, $serviceMode);
        $this->addCoinVendingMachineHandler->execute($request);
    }

    public function removeCoin(Money $money): void
    {
        $this->assertIsInitialized();

        $request = new RemoveCoinVendingMachine($this->id, $money);
        $this->removeCoinVendingMachineHandler->execute($request);
    }

    public function getSummary(OutputInterface $output): void
    {
        $this->assertIsInitialized();

        $request = new GetSummaryVendingMachine($this->id);
        $result = $this->getSummaryVendingMachineHandler->execute($request);

        $this->vendingMachineSummaryPresenter->present($result, $output);
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

    public function hasCoinsToReturn(): bool
    {
        $this->assertIsInitialized();

        $request = new HasCoinsToReturnVendingMachine($this->id);
        return $this->hasCoinsToReturnVendingMachineHandler->execute($request);
    }

    /**
     * @return Money[]
     */
    public function returnCoins(): array
    {
        $this->assertIsInitialized();

        $request = new ReturnCoinsVendingMachine($this->id);
        return $this->returnCoinsVendingMachineHandler->execute($request);
    }

    /**
     * @return Money[]
     */
    public function getInsertedCoins(): array
    {
        $this->assertIsInitialized();

        $request = new GetInsertedCoinsVendingMachine($this->id);
        return $this->getInsertedCoinsVendingMachineHandler->execute($request);
    }

    /**
     * @return array<string, VendingMachineProduct|Money[]>
     * @throws NotEnoughChangeVendingMachineException
     * @throws NotEnoughMoneyVendingMachineException
     */
    public function buyProduct(int $position): array
    {
        $this->assertIsInitialized();

        $request = new SelectProductVendingMachine($this->id, $position);
        return $this->selectProductVendingMachineHandler->execute($request);
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
