<?php

namespace App\Command\VendingMachine\ServiceMode;

use App\Domain\Exception\InvalidCoinTypeException;
use App\Domain\Service\VendingMachineService;
use App\Domain\ValueObject\CoinType;
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

        while (true) {
            $io->title('Vending Machine [Service Mode]');
            $output->writeln('Add coins to the machine. Accepted values: ' . implode(', ', CoinType::VALID_TYPES));
            $io->newLine();
            $helper = $this->getHelper('question');
            $question = new Question('Coin to add: ');

            $coin = $helper->ask($input, $output, $question);
            try {
                $this->vendingMachineService->addCoin((float) $coin);
                break;
            } catch (InvalidCoinTypeException $exception) {
                $output->write(sprintf("\033\143"));
                $io->newLine();
                $output->writeln('<error>Invalid coin type</error>');
            }
        }

        return Command::SUCCESS;
    }
}
