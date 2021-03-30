<?php

namespace App\Port\Command\VendingMachine\ServiceMode;

use App\Application\UseCase\VendingMachine\GetProducts;
use App\Application\UseCase\VendingMachine\GetProductsHandler;
use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineServiceModeRemoveProductCommand extends Command
{
    protected static $defaultName = 'vending-machine:service-mode:remove-products';

    private VendingMachineService $vendingMachineService;
    private GetProductsHandler $getProductsHandler;

    public function __construct(
        VendingMachineService $vendingMachineService,
        GetProductsHandler $getProductsHandler
    ) {
        $this->vendingMachineService = $vendingMachineService;
        $this->getProductsHandler = $getProductsHandler;

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

        $io->title('Vending Machine [Service Mode]');
        $output->writeln('Remove products from the machine.');
        $output->writeln('Available products:');
        $io->newLine();
        $this->vendingMachineService->displayVendingMachineProducts($output, true);
        $io->newLine();
        $helper = $this->getHelper('question');

        $caseRequest = new GetProducts($this->vendingMachineService->getMachineId());
        $productsCount = count($this->getProductsHandler->execute($caseRequest));

        do {
            $question = new Question('Product to remove (Ex: 1): ');
            $productSelection = $helper->ask($input, $output, $question);
        } while (!is_numeric($productSelection) || $productSelection < 0 || $productSelection >= $productsCount);

        $this->vendingMachineService->removeProduct((int)$productSelection);

        return Command::SUCCESS;
    }
}
