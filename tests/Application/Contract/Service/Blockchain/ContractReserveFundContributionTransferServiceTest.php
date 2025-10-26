<?php

namespace App\Tests\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\Blockchain\ContractReserveFundContributionTransferService;
use App\Blockchain\Stellar\Exception\Transaction\GetTransactionException;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractReserveFundContributionOperation;
use App\Entity\Contract\ContractReserveFundContribution;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;
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

class ContractReserveFundContributionTransferServiceTest extends KernelTestCase
{
    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private ContractReserveFundContributionStorageInterface $contributionStorage;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->container = static::getContainer();
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->contributionStorage = $this->container->get('test.App\Persistence\Contract\ContractReserveFundContributionStorageInterface');
    }

    public function testReserveFundContributionTransferred(): void
    {
        $contributionOperationStub = $this->getMockBuilder(ContractReserveFundContributionOperation::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contributionOperationStub->method('contributeToReserveFund')->willReturn($this->createTransactionResponseStub());

        $busStub = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $busStub->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        $transferService = $this->getMockBuilder(ContractReserveFundContributionTransferService::class)
            ->setConstructorArgs([
                $contributionOperationStub,
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractReserveFundContributionTransformer'),
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->persistor,
                $busStub
            ])
            ->onlyMethods([])
            ->getMock();

        $contribution = $this->loadContractReserveFundContribution();
        $transferService->processReserveFundContribution($contribution);

        $updatedContribution = $this->contributionStorage->getByUuidAndStatus($contribution->getUuid(), 'TRANSFERRED');

        $this->assertInstanceOf(ContractReserveFundContribution::class, $updatedContribution);
        $this->assertEquals('TRANSFERRED', $updatedContribution->getStatus());
        $this->assertNotNull($updatedContribution->getTransferredAt());
        $this->assertNotNull($updatedContribution->getContractTrasaction());
    }

    public function testReserveFundContributionTransferFailed(): void
    {
        $contributionOperationStub = $this->getMockBuilder(ContractReserveFundContributionOperation::class)
            ->disableOriginalConstructor()
            ->getMock();

        $exceptionStub = $this->createTransactionExceptionStub();
        $contributionOperationStub->method('contributeToReserveFund')->willThrowException($exceptionStub);

        $busStub = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $transferService = $this->getMockBuilder(ContractReserveFundContributionTransferService::class)
            ->setConstructorArgs([
                $contributionOperationStub,
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractReserveFundContributionTransformer'),
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->persistor,
                $busStub
            ])
            ->onlyMethods([])
            ->getMock();

        $contribution = $this->loadContractReserveFundContribution();
        $transferService->processReserveFundContribution($contribution);

        $updatedContribution = $this->contributionStorage->getByUuidAndStatus($contribution->getUuid(), 'FAILED');

        $this->assertInstanceOf(ContractReserveFundContribution::class, $updatedContribution);
        $this->assertEquals('FAILED', $updatedContribution->getStatus());
        $this->assertNotNull($updatedContribution->getContractTrasaction());
    }

    private function createTransactionExceptionStub(): MockObject
    {
        $exceptionStub = $this->getMockBuilder(GetTransactionException::class)
            ->disableOriginalConstructor()
            ->getMock();

        $exceptionStub->method('getError')->willReturn('Transaction failed');
        $exceptionStub->method('getHash')->willReturn('failedHash123');
        $exceptionStub->method('getCreatedAt')->willReturn('2025-04-15T23:15:41+00:00');

        return $exceptionStub;
    }

    private function createTransactionResponseStub(): MockObject
    {
        $xdrBoolResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_BOOL)])
            ->getMock();

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $transactionResponseStub->expects($this->atLeastOnce())->method('getResultValue')->willReturn($xdrBoolResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->atLeastOnce())->method('getLedger')->willReturn(15986662548);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getCreatedAt')->willReturn('2025-04-15T23:15:41+00:00');

        return $transactionResponseStub;
    }

    private function loadContractReserveFundContribution(): ContractReserveFundContribution
    {
        $issuer = EntityGenerator::createIssuer();
        $token = EntityGenerator::createToken();
        $investor = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract = EntityGenerator::createActiveContract($issuer, $token);
        $contribution = EntityGenerator::createContractReserveFundContribution($investor, $contract);
        $contribution->setStatus('RECEIVED');

        $this->persistor->persistAndFlush([
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract,
            $contribution
        ]);

        return $contribution;
    }
}
