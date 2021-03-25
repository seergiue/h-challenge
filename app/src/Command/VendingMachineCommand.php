<?php

namespace App\Command;

use App\Domain\Model\Coin;
use App\Domain\Model\Product;
use App\Domain\Model\VendingMachine;
use App\Domain\Model\VendingMachineProduct;
use App\Domain\Model\VendingMachineWallet;
use App\Domain\Model\VendingMachineWalletCoin;
use App\Domain\ValueObject\Money;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'vending-machine:start';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = [
            new VendingMachineProduct(new Product('Water', Money::fromValue(0.65)), 10),
            new VendingMachineProduct(new Product('Juice', Money::fromValue(1.00)), 10),
            new VendingMachineProduct(new Product('Soda', Money::fromValue(1.50)), 10),
        ];
        $coins = [
            new VendingMachineWalletCoin(new Coin(Money::fromValue(0.05)), 10),
            new VendingMachineWalletCoin(new Coin(Money::fromValue(0.10)), 10),
            new VendingMachineWalletCoin(new Coin(Money::fromValue(0.25)), 10),
            new VendingMachineWalletCoin(new Coin(Money::fromValue(1.00)), 10),
        ];

        $wallet = new VendingMachineWallet($coins);
        $vendingMachine = new VendingMachine($products, $wallet);

        while(true) {
            $helper = $this->getHelper('question');
            $question = new Question('Demo action: ');

            $action = $helper->ask($input, $output, $question);

            if ($action === 'exit') {
                break;
            }

            $output->writeln('Action name is ' . $action);
        }

        return Command::SUCCESS;
    }
}
