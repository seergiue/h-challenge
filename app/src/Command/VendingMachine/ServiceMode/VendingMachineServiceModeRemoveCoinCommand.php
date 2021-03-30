<?php

namespace App\Command\VendingMachine\ServiceMode;

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

class VendingMachineServiceModeRemoveCoinCommand extends Command
{
    protected static $defaultName = 'vending-machine:service-mode:remove-coins';

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
        $output->writeln('Remove coins from the machine. Accepted values: ' . implode(', ', $moneyValues));
        $io->newLine();
        $helper = $this->getHelper('question');

        do {
            $question = new Question('Coin to remove: ');
            $value = $helper->ask($input, $output, $question);
        } while (!in_array($value, $moneyValues));

        $this->vendingMachineService->removeCoin(Money::EUR($value * 100));

        return Command::SUCCESS;
    }
}
