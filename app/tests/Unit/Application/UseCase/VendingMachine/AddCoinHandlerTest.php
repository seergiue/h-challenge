<?php

namespace App\Tests\Unit\Application\UseCase\VendingMachine;

use App\Application\UseCase\VendingMachine\AddCoin;
use App\Application\UseCase\VendingMachine\AddCoinHandler;
use App\Domain\Exception\InvalidVendingMachineMoneyValueException;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Service\Repository\VendingMachineRepository;
use Money\Money;
use PHPUnit\Framework\TestCase;

class AddCoinHandlerTest extends TestCase
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

        $case = new AddCoinHandler($this->vendingMachineRepositoryMock);
        $request = new AddCoin(
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

        $case = new AddCoinHandler($this->vendingMachineRepositoryMock);
        $request = new AddCoin(
            $expectedVendingMachine->getId(),
            Money::EUR(25),
            1,
            false
        );
        $case->execute($request);
    }
}
