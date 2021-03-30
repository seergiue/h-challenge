<?php

namespace App\Port\Command\VendingMachine;

use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\MoneyValue;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\MoneyFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineInsertCoinCommand extends Command
{
    protected static $defaultName = 'vending-machine:insert-coins';

    private VendingMachineService $vendingMachineService;

    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;

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

        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        $this->printInsertedCoins($output, $moneyFormatter);

        $moneyValues = array_map(
            function (Money $money) use ($moneyFormatter) {
                return $moneyFormatter->format($money);
            },
            MoneyValue::getAll(true)
        );

        $output->writeln('Add coins to the machine. Accepted values: ' . implode(', ', $moneyValues));
        $io->newLine();
        $helper = $this->getHelper('question');

        do {
            $confirmation = new ConfirmationQuestion('Do you want to insert coins? [y/n]: ', false);

            if ($addCoins = $helper->ask($input, $output, $confirmation)) {
                do {
                    $question = new Question('Coin to add: ');
                    $value = $helper->ask($input, $output, $question);
                } while (!in_array($value, $moneyValues));

                $this->vendingMachineService->addCoin(Money::EUR($value * 100));
                $output->writeln('Coin added!' . PHP_EOL);
            }
        } while ($addCoins);

        return Command::SUCCESS;
    }

    private function printInsertedCoins(OutputInterface $output, MoneyFormatter $moneyFormatter): void
    {
        $hasInsertedCoins = $this->vendingMachineService->hasCoinsToReturn();

        if ($hasInsertedCoins) {
            $insertedCoins = $this->vendingMachineService->getInsertedCoins();
            $insertedCoinsValues = array_map(
                function (Money $money) use ($moneyFormatter) {
                    return $moneyFormatter->format($money);
                },
                $insertedCoins
            );

            $output->writeln('Inserted coins: ' . implode(', ', $insertedCoinsValues));
        } else {
            $output->writeln('There are no inserted coins. Add some!');
        }
    }
}
