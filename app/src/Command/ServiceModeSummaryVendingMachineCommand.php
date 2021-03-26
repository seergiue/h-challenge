<?php

namespace App\Command;

use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServiceModeSummaryVendingMachineCommand extends Command
{
    protected static $defaultName = 'vending-machine:service-mode:summary';

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

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->vendingMachineService->isInitialized()) {
            $output->writeln('<error>Run vending-machine:start</error>');
            return Command::SUCCESS;
        }

        $io = new SymfonyStyle($input, $output);

        $io->title('Vending Machine [Service Mode]');
        $io->newLine();
        $this->vendingMachineService->getSummary($output);
        $helper = $this->getHelper('question');
        $question = new Question('Press enter to go back');

        $helper->ask($input, $output, $question);

        return Command::SUCCESS;
    }
}
