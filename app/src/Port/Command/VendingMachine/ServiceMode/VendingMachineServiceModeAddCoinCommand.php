<?php

namespace App\Port\Command\VendingMachine\ServiceMode;

use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\MoneyValue;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineServiceModeAddCoinCommand extends Command
{
    protected static $defaultName = 'vending-machine:service-mode:add-coins';

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

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->vendingMachineService->isInitialized()) {
            $output->writeln('<error>Run vending-machine:start</error>');
            return Command::SUCCESS;
        }

        $io = new SymfonyStyle($input, $output);

        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        $moneyValues = array_map(
            function (Money $money) use ($moneyFormatter) {
                return $moneyFormatter->format($money);
            },
            MoneyValue::getAll(true)
        );

        $io->title('Vending Machine [Service Mode]');
        $output->writeln('Add coins to the machine. Accepted values: ' . implode(', ', $moneyValues));
        $io->newLine();
        $helper = $this->getHelper('question');

        do {
            $question = new Question('Coin to add: ');
            $value = $helper->ask($input, $output, $question);
        } while (!in_array($value, $moneyValues));

        do {
            $question = new Question('Quantity to add: ');
            $quantity = $helper->ask($input, $output, $question);
        } while (!is_numeric($quantity) || (is_numeric($quantity) && $quantity < 1));

        $this->vendingMachineService->addCoin(Money::EUR($value * 100), $quantity, true);

        return Command::SUCCESS;
    }
}
