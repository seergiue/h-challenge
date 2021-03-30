<?php

namespace App\Port\Command\VendingMachine;

use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class VendingMachineListProductsCommand extends Command
{
    protected static $defaultName = 'vending-machine:list-products';

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

        $this->vendingMachineService->displayVendingMachineProducts($output);
        $helper = $this->getHelper('question');
        $question = new Question('Press enter to go back');

        $helper->ask($input, $output, $question);

        return Command::SUCCESS;
    }
}
