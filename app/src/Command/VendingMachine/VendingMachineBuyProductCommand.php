<?php

namespace App\Command\VendingMachine;

use App\Application\VendingMachine\GetVendingMachineProducts;
use App\Application\VendingMachine\GetVendingMachineProductsHandler;
use App\Domain\Exception\NotEnoughChangeVendingMachineException;
use App\Domain\Exception\NotEnoughMoneyVendingMachineException;
use App\Domain\Exception\ProductOutOfStockVendingMachineException;
use App\Domain\Model\Coin;
use App\Domain\Service\VendingMachineService;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineBuyProductCommand extends Command
{
    protected static $defaultName = 'vending-machine:buy-product';

    private VendingMachineService $vendingMachineService;
    private GetVendingMachineProductsHandler $getVendingMachineProductsHandler;

    public function __construct(
        VendingMachineService $vendingMachineService,
        GetVendingMachineProductsHandler $getVendingMachineProductsHandler
    ) {
        $this->vendingMachineService = $vendingMachineService;
        $this->getVendingMachineProductsHandler = $getVendingMachineProductsHandler;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->vendingMachineService->isInitialized()) {
            $output->writeln('<error>Run vending-machine:start</error>');
            return Command::SUCCESS;
        }

        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        $caseRequest = new GetVendingMachineProducts($this->vendingMachineService->getMachineId());
        $productsCount = count($this->getVendingMachineProductsHandler->execute($caseRequest));

        $this->vendingMachineService->displayVendingMachineProducts($output, true);

        do {
            $question = new Question('Select a product (Ex: 1): ');
            $selectedProductPosition = $helper->ask($input, $output, $question);
        } while (!is_numeric($selectedProductPosition) || $selectedProductPosition < 0 || $selectedProductPosition >= $productsCount);

        try {
            $result = $this->vendingMachineService->buyProduct($selectedProductPosition);
            $this->printDrink($output);
            $output->writeln('<fg=green>Enjoy your ' . $result['product']->getProduct()->getName() . '</>');

            if (!empty($result['change'])) {
                $io->newLine();
                $changeValues = array_map(function(Money $money) use ($moneyFormatter) {
                    return $moneyFormatter->format($money);
                }, $result['change']);
                $output->writeln('<fg=yellow>This is your change: ' . implode(', ', $changeValues) . '</>');
            }
        } catch(ProductOutOfStockVendingMachineException $exception) {
            $io->newLine();
            $output->writeln('<error>This product is out of stock!</error>');
        } catch(NotEnoughMoneyVendingMachineException $exception) {
            $io->newLine();
            $output->writeln('<error>Not enough money!</error>');
        } catch(NotEnoughChangeVendingMachineException $exception) {
            $io->newLine();
            $output->writeln('<error>' . $exception->getMessage() . '</error>');
        }

        $io->newLine();
        $waitExit = new Question('Press enter to go back');
        $helper->ask($input, $output, $waitExit);

        return Command::SUCCESS;
    }

    private function printDrink(OutputInterface $output): void
    {
        $output->writeln('
               .
              ..
              ...
            \~~~~~/
             \   /
              \ /
               V
               |
               |
              ---
        ');
    }
}