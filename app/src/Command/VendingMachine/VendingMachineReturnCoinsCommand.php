<?php

namespace App\Command\VendingMachine;

use App\Domain\Model\Coin;
use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class VendingMachineReturnCoinsCommand extends Command
{
    protected static $defaultName = 'vending-machine:return-coins';

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

        if ($this->vendingMachineService->hasCoinsToReturn()) {
            $returnedCoins = $this->vendingMachineService->returnCoins();
            $returnedCoinsValue = array_map(function (Coin $coin) {
                return $coin->getAmount()->getValue();
            }, $returnedCoins);

            $output->writeln('Coins returned: ' . implode(', ', $returnedCoinsValue));
        } else {
            $output->writeln('There are no coins to return :)');
        }

        $helper = $this->getHelper('question');
        $question = new Question(PHP_EOL . 'Press enter to go back');

        $helper->ask($input, $output, $question);

        return Command::SUCCESS;
    }
}
