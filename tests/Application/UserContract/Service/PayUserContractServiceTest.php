<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Application\UserContract\Service;

use App\Application\UserContract\Service\PayUserContractService;
use App\Application\UserContract\Service\ProcessUserContractService;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\PayUserContractOperation;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\UserContractPayment;
use App\Persistence\PersistorInterface;
use App\Persistence\UserContract\UserContractPaymentStorageInterface;
use App\Tests\EntityGenerator;
use App\Tests\GenerateXdrMapEntryMock;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Xdr\XdrInt128Parts;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Soneso\StellarSDK\Xdr\XdrSCValType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class PayUserContractServiceTest extends KernelTestCase
{
    use GenerateXdrMapEntryMock;

    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private UserContractPaymentStorageInterface $userContractPaymentStorage;

    protected function setUp(): void
    {
        parent::setUp(); 

        self::bootKernel(); 
        $this->container = static::getContainer(); 
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->userContractPaymentStorage = $this->container->get('test.App\Persistence\UserContract\UserContractPaymentStorageInterface');

    }

    public function testPayUserContractSuccess(): void
    {
        $transactionResponseStub = $this->createTransactionResponseStub();

        $payUserContractOperationStub = $this->getMockBuilder(PayUserContractOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $payUserContractOperationStub->method('payUserContract')->willReturn($transactionResponseStub);
        $busStub = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $busStub->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        /**
         * @var PayUserContractService $payUserContractService
         */
        $payUserContractService = $this->getMockBuilder(PayUserContractService::class)
            ->setConstructorArgs([
                $payUserContractOperationStub,
                $this->persistor,
                $this->container->get('test.App\Application\UserContract\Mapper\UserInvestmentTrxResultMapper'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\UserContract\Transformer\UserContractEntityTransformer'),
                $this->container->get('test.App\Application\UserContract\Transformer\UserContractPaymentEntityTransformer'),
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $busStub
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $userContractPayment = $this->loadUserContractPayment();
        $payUserContractService->payUserContract($userContractPayment);
        $userContractPayment = $this->userContractPaymentStorage->getById($userContractPayment->getId());

        $this->assertEquals('CONFIRMED', $userContractPayment->getStatus());
    }

    private function createTransactionResponseStub(): MockObject
    {
        $xdrMapResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_MAP)])
            ->getMock()
        ;

        $xdrMapResult = [
            $this->generateEntry('deposited', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 10000000000)),
            $this->generateEntry('accumulated_interests', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 236000000)),
            $this->generateEntry('total', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 1236000000)),
            $this->generateEntry('claimable_ts', 'getU64', XdrSCValType::SCV_U64,  16652354),
            $this->generateEntry('last_transfer_ts', 'getU64', XdrSCValType::SCV_U64, strtotime('now')),
            $this->generateEntry('status', 'getU32', XdrSCValType::SCV_U32, 4),
            $this->generateEntry('regular_payment', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 83000000)),
            $this->generateEntry('paid', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 83000000)),
        ];

        $xdrMapResultMock->method('getMap')->willReturn($xdrMapResult);

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $transactionResponseStub->expects($this->once())->method('getResultValue')->willReturn($xdrMapResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->atLeastOnce())->method('getLedger')->willReturn(15986662548);

        return $transactionResponseStub;
    }

    private function loadUserContractPayment(): UserContractPayment
    {
        $issuer         = EntityGenerator::createIssuer();
        $token          = EntityGenerator::createToken();
        $investor       = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract       = EntityGenerator::createActiveContract($issuer, $token);
        $userContract   = EntityGenerator::createPendingUserContract($contract, $investor, $investorWallet);
        $userContractPayment = EntityGenerator::createPendingUserContractPayment($userContract);


        $this->persistor->persistAndFlush([
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract,
            $userContract,
            $userContractPayment
        ]);

        return $userContractPayment;
    }
}
