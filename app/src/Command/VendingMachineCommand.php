<?php

namespace App\Command;

use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'vending-machine:start';

    const ACTION_LIST_PRODUCTS = 1;
    const ACTION_SELECT_PRODUCT = 2;
    const ACTION_INSERT_COINS = 3;
    const ACTION_RETURN_COINS = 4;
    const ACTION_SERVICE = 5;
    const ACTION_EXIT = 6;

    private VendingMachineService $vendingMachineService;

    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write(sprintf("\033\143"));
        $io = new SymfonyStyle($input, $output);
        $this->vendingMachineService->newMachine();

        $io->title('Welcome to the Vending Machine');

        while(true) {
            $action = $this->askAction($input, $output);
            $io->newLine();
            $output->write(sprintf("\033\143"));

            switch ($action) {
                case self::ACTION_LIST_PRODUCTS:
                    $this->vendingMachineService->getvendingMachineProducts($output);
                    break;
                case self::ACTION_SELECT_PRODUCT:
                    break;
                case self::ACTION_INSERT_COINS:
                    break;
                case self::ACTION_RETURN_COINS:
                    break;
                case self::ACTION_SERVICE:
                    break;
                case self::ACTION_EXIT:
                    return Command::SUCCESS;
                default:
                    $output->writeln(PHP_EOL . '<error>Invalid action</error>');
            }

            $output->writeln(PHP_EOL . '<info>=====================================================</>' . PHP_EOL);
        }
    }

    private function askAction(InputInterface $input, OutputInterface $output): string
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Number', 'Action'])
            ->setHeaderTitle('Actions')
            ->setStyle('box')
            ->setRows([
                [self::ACTION_LIST_PRODUCTS, 'List products'],
                [self::ACTION_SELECT_PRODUCT, 'Select product'],
                [self::ACTION_INSERT_COINS, 'Insert coins'],
                [self::ACTION_RETURN_COINS, 'Return coins'],
                [self::ACTION_SERVICE, 'Service'],
                [self::ACTION_EXIT, 'Exit'],
            ]);
        $table->render();

        $helper = $this->getHelper('question');
        $question = new Question('Select an action (Ex: 1): ');

        return $helper->ask($input, $output, $question);
    }
}
