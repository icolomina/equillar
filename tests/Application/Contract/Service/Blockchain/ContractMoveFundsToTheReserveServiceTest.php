<?php

namespace App\Tests\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\Blockchain\ContractMoveFundsToTheReserveService;
use App\Blockchain\Stellar\Exception\Transaction\GetTransactionException;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractAvailableToReserveFundOperation;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Entity\Contract\ContractBalanceMovement;
use App\Persistence\Contract\ContractBalanceMovementStorageInterface;
use App\Persistence\PersistorInterface;
use App\Tests\EntityGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Soneso\StellarSDK\Xdr\XdrSCValType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ContractMoveFundsToTheReserveServiceTest extends KernelTestCase
{
    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private ContractBalanceMovementStorageInterface $contractBalanceMovementStorage;

    protected function setUp(): void
    {
        parent::setUp(); 

        self::bootKernel(); 
        $this->container = static::getContainer(); 
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->contractBalanceMovementStorage = $this->container->get('test.App\Persistence\Contract\ContractBalanceMovementStorageInterface');
    }

    public function testMoveAvailableFundsToTheReserveSuccess(): void
    {
        $transactionResponseStub = $this->createTransactionResponseStub();
        $contractAvailableToReserveFundOperationStub = $this->getMockBuilder(ContractAvailableToReserveFundOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $contractAvailableToReserveFundOperationStub->method('moveAvailableFundsToReserve')->willReturn($transactionResponseStub);

        $busStub = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $busStub->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        /**
         * @var ContractMoveFundsToTheReserveService $contractMoveFundsToTheReserveServiceStub
         */
        $contractMoveFundsToTheReserveServiceStub = $this->getMockBuilder(ContractMoveFundsToTheReserveService::class)
            ->setConstructorArgs([
                $contractAvailableToReserveFundOperationStub,
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractBalanceMovementTransformer'),
                $busStub,
                $this->persistor
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $contractBalanceMovement = $this->loadContractBalanceMovement();
        $contractMoveFundsToTheReserveServiceStub->moveAvailableFundsToTheReserve($contractBalanceMovement);
        
        // Reload from database to check the updated state
        $contractBalanceMovements = $this->contractBalanceMovementStorage->getAll();
        $updatedMovement = array_pop($contractBalanceMovements);

        $this->assertEquals('MOVED', $updatedMovement->getStatus());
        $this->assertNotNull($updatedMovement->getMovedAt());
        $this->assertNotNull($updatedMovement->getContractTransaction());
        $this->assertEquals('886996787366366355553', $updatedMovement->getContractTransaction()->getTrxHash());
    }

    public function testMoveAvailableFundsToTheReserveFailure(): void
    {
        $getTransactionExceptionStub = $this->createGetTransactionExceptionStub();
        $contractAvailableToReserveFundOperationStub = $this->getMockBuilder(ContractAvailableToReserveFundOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $contractAvailableToReserveFundOperationStub->method('moveAvailableFundsToReserve')->willThrowException($getTransactionExceptionStub);

        $busStub = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $busStub->expects($this->never())->method('dispatch');

        /**
         * @var ContractMoveFundsToTheReserveService $contractMoveFundsToTheReserveServiceStub
         */
        $contractMoveFundsToTheReserveServiceStub = $this->getMockBuilder(ContractMoveFundsToTheReserveService::class)
            ->setConstructorArgs([
                $contractAvailableToReserveFundOperationStub,
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractBalanceMovementTransformer'),
                $busStub,
                $this->persistor
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $this->expectException(ContractExecutionFailedException::class);
        $contractBalanceMovement = $this->loadContractBalanceMovement();
        $contractMoveFundsToTheReserveServiceStub->moveAvailableFundsToTheReserve($contractBalanceMovement);
        
        // Reload from database to check the updated state
        $contractBalanceMovements = $this->contractBalanceMovementStorage->getAll();
        $updatedMovement = array_pop($contractBalanceMovements);

        $this->assertEquals('FAILED', $updatedMovement->getStatus());
        $this->assertNull($updatedMovement->getMovedAt());
        $this->assertNotNull($updatedMovement->getContractTransaction());
        $this->assertEquals('886996787366366355553', $updatedMovement->getContractTransaction()->getTrxHash());
    }

    private function createTransactionResponseStub(): MockObject
    {
        $xdrVoidResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_VOID)])
            ->getMock()
        ;

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $transactionResponseStub->expects($this->once())->method('getResultValue')->willReturn($xdrVoidResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->atLeastOnce())->method('getCreatedAt')->willReturn('2025-04-15T23:15:41+00:00');
        $transactionResponseStub->expects($this->once())->method('getLedger')->willReturn(15986662548);

        return $transactionResponseStub;
    }

    private function createGetTransactionExceptionStub(): MockObject
    {
        $failedTransactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $failedTransactionResponseStub->method('getTxHash')->willReturn('886996787366366355553');
        $failedTransactionResponseStub->method('getCreatedAt')->willReturn('2025-04-15T23:15:41+00:00');

        $exceptionStub = $this->getMockBuilder(GetTransactionException::class)
            ->setConstructorArgs([$failedTransactionResponseStub])
            ->getMock()
        ;

        $exceptionStub->method('getError')->willReturn('Transaction failed: Insufficient funds');
        $exceptionStub->method('getHash')->willReturn('886996787366366355553');
        $exceptionStub->method('getCreatedAt')->willReturn('2025-04-15T23:15:41+00:00');

        return $exceptionStub;
    }

    private function loadContractBalanceMovement(): ContractBalanceMovement
    {
        $issuer         = EntityGenerator::createIssuer();
        $token          = EntityGenerator::createToken();
        $investor       = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract       = EntityGenerator::createActiveContract($issuer, $token);
        $contractBalanceMovement = EntityGenerator::createContractBalanceMovement($issuer, $contract);

        $this->persistor->persistAndFlush([
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract,
            $contractBalanceMovement
        ]);

        return $contractBalanceMovement;
    }
}
