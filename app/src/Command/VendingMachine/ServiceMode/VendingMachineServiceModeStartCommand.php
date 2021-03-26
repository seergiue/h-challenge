<?php

namespace App\Command\VendingMachine\ServiceMode;

use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineServiceModeStartCommand extends Command
{
    const SERVICE_SUMMARY = 1;
    const SERVICE_ADD_COIN = 2;
    const SERVICE_REMOVE_COIN = 3;
    const SERVICE_EXIT = 4;

    protected static $defaultName = 'vending-machine:service-mode:start';

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

        $output->write(sprintf("\033\143"));
        $io = new SymfonyStyle($input, $output);
        while(true) {
            $output->write(sprintf("\033\143"));
            $io->title('Vending Machine [Service Mode]');

            $table = new Table($output);
            $table
                ->setHeaders(['Number', 'Action'])
                ->setHeaderTitle('Actions')
                ->setStyle('box')
                ->setRows([
                    [self::SERVICE_SUMMARY, 'Summary'],
                    [self::SERVICE_ADD_COIN, 'Add coins'],
                    [self::SERVICE_REMOVE_COIN, 'Remove coins'],
                    [self::SERVICE_EXIT, 'Go back'],
                ]);
            $table->render();

            $helper = $this->getHelper('question');
            $question = new Question('Select an action (Ex: 1): ');

            $action = $helper->ask($input, $output, $question);

            switch ($action) {
                case self::SERVICE_SUMMARY:
                    $output->write(sprintf("\033\143"));
                    $command = $this->getApplication()->find('vending-machine:service-mode:summary');
                    $command->run($input, $output);
                    break;
                case self::SERVICE_ADD_COIN:
                    $output->write(sprintf("\033\143"));
                    $command = $this->getApplication()->find('vending-machine:service-mode:add-coins');
                    $command->run($input, $output);
                    break;
                case self::SERVICE_REMOVE_COIN:
                    $output->write(sprintf("\033\143"));
                    $command = $this->getApplication()->find('vending-machine:service-mode:remove-coins');
                    $command->run($input, $output);
                    break;
                case self::SERVICE_EXIT:
                    $output->write(sprintf("\033\143"));
                    return Command::SUCCESS;
                default:
                    $output->write(sprintf("\033\143"));
            }
        }
    }
}
