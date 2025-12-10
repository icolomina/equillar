<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Application\UserContract\Service;

use App\Application\UserContract\Service\ProcessUserContractService;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\UserContract;
use App\Persistence\PersistorInterface;
use App\Persistence\UserContract\UserContractStorageInterface;
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

class ProcessUserContractServiceTest extends KernelTestCase
{
    use GenerateXdrMapEntryMock;

    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private UserContractStorageInterface $userContractStorage;

    protected function setUp(): void
    {
        parent::setUp(); 

        self::bootKernel(); 
        $this->container = static::getContainer(); 
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->userContractStorage = $this->container->get('test.App\Persistence\UserContract\UserContractStorageInterface');

    }

    public function testUserContractSuccess(): void
    {
        $transactionResponseStub = $this->createTransactionResponseStub();
        $processTransactionServiceStub = $this->createProcessTransactionServiceStub($transactionResponseStub);
        
        /**
         * @var ProcessUserContractService $processUserContractService
         */
        $processUserContractService = $this->getMockBuilder(ProcessUserContractService::class)
            ->setConstructorArgs([
                $processTransactionServiceStub,
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->persistor,
                $this->container->get('test.App\Application\UserContract\Mapper\UserInvestmentTrxResultMapper'),
                $this->createMessageBusStub()
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $userContract = $this->loadContractAndUserContract();
        $processUserContractService->processUserContractTransaction($userContract);
        $userContractDb = $this->userContractStorage->getByUserAndContract($userContract->getUsr(), $userContract->getContract());
        
        $this->assertInstanceOf(UserContract::class, $userContractDb);
        $this->assertEquals(23.6, $userContractDb->getInterests());
        $this->assertEquals(123.6, $userContractDb->getTotal());
        $this->assertEquals(100.0, $userContractDb->getBalance());
    }

    private function createTransactionResponseStub(): MockObject
    {
        $xdrMapResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_MAP)])
            ->getMock()
        ;

        $xdrMapResult = [
            $this->generateEntry('deposited', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 1000000000)),
            $this->generateEntry('accumulated_interests', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 236000000)),
            $this->generateEntry('total', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 1236000000)),
            $this->generateEntry('claimable_ts', 'getU64', XdrSCValType::SCV_U64,  16652354),
            $this->generateEntry('last_transfer_ts', 'getU64', XdrSCValType::SCV_U64,  0),
            $this->generateEntry('status', 'getU32', XdrSCValType::SCV_U32, 1),
            $this->generateEntry('regular_payment', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 33651)),
            $this->generateEntry('paid', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 0)),
        ];

        $xdrMapResultMock->method('getMap')->willReturn($xdrMapResult);

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $transactionResponseStub->expects($this->once())->method('getResultValue')->willReturn($xdrMapResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->atLeastOnce())->method('getLedger')->willReturn(15986662548);

        return $transactionResponseStub;
    }

    private function createProcessTransactionServiceStub(MockObject $transactionResponseStub)
    {
        $processTransactionServiceStub = $this->getMockBuilder(ProcessTransactionService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $processTransactionServiceStub->expects($this->once())->method('waitForTransaction')->willReturn($transactionResponseStub);
        return $processTransactionServiceStub;
    }

    private function createMessageBusStub(): MockObject
    {
        $messageBusStub = $this->createStub(MessageBusInterface::class);
        $messageBusStub->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        return $messageBusStub;
    }

    private function loadContractAndUserContract(): UserContract
    {
        $organization   = EntityGenerator::createOrganization();
        $issuer         = EntityGenerator::createIssuer($organization);
        $token          = EntityGenerator::createToken();
        $investor       = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract       = EntityGenerator::createActiveContract($issuer, $token);
        $userContract   = EntityGenerator::createPendingUserContract($contract, $investor, $investorWallet);

        $this->persistor->persistAndFlush([
            $organization,
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract,
            $userContract
        ]);

        return $userContract;
    }
}
