<?php

namespace App\Command;

use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'vending-machine:start';

    private VendingMachineService $vendingMachineService;

    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $vendingMachineManager = $this->vendingMachineService->getManager();
        $vendingMachineManager->newMachine();

        $output->writeln(PHP_EOL . 'Welcome to the Vending Machine' . PHP_EOL);

        while(true) {
            $table = new Table($output);
            $table
                ->setHeaders(['Number', 'Action'])
                ->setHeaderTitle('Actions')
                ->setStyle('box')
                ->setRows([
                    ['1', 'List products'],
                    ['2', 'Select product'],
                    ['3', 'Insert coins'],
                    ['4', 'Return coins'],
                    ['5', 'Service'],
                    ['6', 'Exit'],
                ]);
            $table->render();

            $helper = $this->getHelper('question');
            $question = new Question('Select an action (Ex: 1): ');

            $action = $helper->ask($input, $output, $question);

            switch ($action) {
                case '1':
                    $vendingMachineManager->getvendingMachineProducts($output);
                    break;
                case '6':
                    return Command::SUCCESS;
                default:
                    $output->writeln(PHP_EOL . '<error>Invalid action</error>');
            }
        }
    }
}
