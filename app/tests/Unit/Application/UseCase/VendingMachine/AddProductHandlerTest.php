<?php

namespace App\Tests\Unit\Application\UseCase\VendingMachine;

use App\Application\UseCase\VendingMachine\AddProduct;
use App\Application\UseCase\VendingMachine\AddProductHandler;
use App\Domain\Exception\InvalidVendingMachineProductPositionException;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Service\Repository\VendingMachineRepository;
use PHPUnit\Framework\TestCase;

class AddProductHandlerTest extends TestCase
{
    private $vendingMachineRepositoryMock;

    private const VALID_POSITION = 0;
    private const INVALID_POSITION = 999;
    private const QUANTITY = 1;

    public function setUp(): void
    {
        $this->vendingMachineRepositoryMock = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldThrowInvalidVendingMachineProductPosition()
    {
        $expectedVendingMachine = VendingMachine::withProductsAndWallet();

        $this->vendingMachineRepositoryMock
            ->expects(self::once())
            ->method('findById')
            ->willReturn($expectedVendingMachine);
        $this->expectException(InvalidVendingMachineProductPositionException::class);

        $case = new AddProductHandler($this->vendingMachineRepositoryMock);
        $request = new AddProduct(
            $expectedVendingMachine->getId(),
            self::INVALID_POSITION,
            self::QUANTITY
        );
        $case->execute($request);
    }

    public function testItShouldAddProduct()
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

        $case = new AddProductHandler($this->vendingMachineRepositoryMock);
        $request = new AddProduct(
            $expectedVendingMachine->getId(),
            self::VALID_POSITION,
            self::QUANTITY
        );
        $case->execute($request);
    }
}
