<?php

namespace App\Tests\Unit\Application\VendingMachine;

use App\Application\VendingMachine\AddCoinVendingMachine;
use App\Application\VendingMachine\AddCoinVendingMachineHandler;
use App\Domain\Exception\InvalidVendingMachineMoneyValueException;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Service\Repository\VendingMachineRepository;
use Money\Money;
use PHPUnit\Framework\TestCase;

class AddCoinVendingMachineHandlerTest extends TestCase
{
    private $vendingMachineRepositoryMock;

    public function setUp(): void
    {
        $this->vendingMachineRepositoryMock = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldThrowInvalidMoneyValueException()
    {
        $expectedVendingMachine = VendingMachine::withProductsAndWallet();

        $this->vendingMachineRepositoryMock
            ->expects(self::once())
            ->method('findById')
            ->willReturn($expectedVendingMachine);
        $this->expectException(InvalidVendingMachineMoneyValueException::class);

        $case = new AddCoinVendingMachineHandler($this->vendingMachineRepositoryMock);
        $request = new AddCoinVendingMachine(
            $expectedVendingMachine->getId(),
            Money::EUR(999),
            1,
            false
        );
        $case->execute($request);
    }

    public function testItShouldAddCoin()
    {
        $expectedVendingMachine = VendingMachine::withProductsAndWallet();

        $this->vendingMachineRepositoryMock
            ->expects(self::once())
            ->method('findById')
            ->willReturn($expectedVendingMachine);
        $this->vendingMachineRepositoryMock
            ->expects(self::once())
            ->method('save')
            ->with($expectedVendingMachine);

        $case = new AddCoinVendingMachineHandler($this->vendingMachineRepositoryMock);
        $request = new AddCoinVendingMachine(
            $expectedVendingMachine->getId(),
            Money::EUR(25),
            1,
            false
        );
        $case->execute($request);
    }
}
