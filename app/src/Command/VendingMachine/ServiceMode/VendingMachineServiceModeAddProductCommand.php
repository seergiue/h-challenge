<?php

namespace App\Command\VendingMachine\ServiceMode;

use App\Application\VendingMachine\GetVendingMachineProducts;
use App\Application\VendingMachine\GetVendingMachineProductsHandler;
use App\Domain\Service\VendingMachineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineServiceModeAddProductCommand extends Command
{
    protected static $defaultName = 'vending-machine:service-mode:add-products';

    private VendingMachineService $vendingMachineService;
    private GetVendingMachineProductsHandler $getVendingMachineProductsHandler;

    public function __construct(
        VendingMachineService $vendingMachineService,
        GetVendingMachineProductsHandler $getVendingMachineProductsHandler
    ) {
        $this->vendingMachineService = $vendingMachineService;
        $this->getVendingMachineProductsHandler = $getVendingMachineProductsHandler;

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
        $output->writeln('Add products to the machine.');
        $output->writeln('Available products:');
        $io->newLine();
        $this->vendingMachineService->getVendingMachineProductsInServiceMode($output);
        $io->newLine();
        $helper = $this->getHelper('question');

        $caseRequest = new GetVendingMachineProducts($this->vendingMachineService->getMachineId());
        $productsCount = count($this->getVendingMachineProductsHandler->execute($caseRequest));

        do {
            $question = new Question('Product to add (Ex: 1): ');
            $productSelection = $helper->ask($input, $output, $question);
        } while(!is_numeric($productSelection) || $productSelection < 0 || $productSelection >= $productsCount);

        do {
            $question = new Question('Quantity to add: ');
            $quantity = $helper->ask($input, $output, $question);
        } while(!is_numeric($quantity) || (is_numeric($quantity) && $quantity < 1));

        $this->vendingMachineService->addProduct((int)$productSelection, $quantity);

        return Command::SUCCESS;
    }
}