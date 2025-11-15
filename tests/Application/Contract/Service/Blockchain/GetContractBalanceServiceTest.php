<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\Blockchain\ContractBalanceGetAndUpdateService;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\GetContractBalanceOperation;
use App\Entity\Contract\Contract;
use App\Persistence\Contract\ContractBalanceStorageInterface;
use App\Persistence\PersistorInterface;
use App\Tests\EntityGenerator;
use App\Tests\GenerateXdrMapEntryMock;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Xdr\XdrInt128Parts;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Soneso\StellarSDK\Xdr\XdrSCValType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetContractBalanceServiceTest extends KernelTestCase
{
    use GenerateXdrMapEntryMock;
    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private ContractBalanceStorageInterface $contractBalanceStorage;

    protected function setUp(): void
    {
        parent::setUp(); 

        self::bootKernel(); 
        $this->container = static::getContainer(); 
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->contractBalanceStorage = $this->container->get('test.App\Persistence\Contract\ContractBalanceStorageInterface');

    }

    public function testGetBalance(): void
    {
        $getBalanceOperationStub = $this->getMockBuilder(GetContractBalanceOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $getBalanceOperationStub->method('getContractBalance')->willReturn($this->createTransactionResponseStub());

        /**
         * @var GetContractBalanceService getContractBalanceServiceStub
         */
        $getContractBalanceServiceStub = $this->getMockBuilder(ContractBalanceGetAndUpdateService::class)
            ->setConstructorArgs([
                $getBalanceOperationStub,
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->container->get('test.App\Application\Contract\Mapper\GetContractBalanceMapper'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractBalanceEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->persistor
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $contract = $this->loadContract();
        $getContractBalanceServiceStub->getContractBalance($contract);
        $contractBalance = $this->contractBalanceStorage->getLastBalanceByContract($contract);

        $this->assertEquals(1000, $contractBalance->getAvailable());
        $this->assertEquals(100, $contractBalance->getReserveFund());
        $this->assertEquals(10, $contractBalance->getComission());
    }

    private function createTransactionResponseStub(): MockObject
    {
        $xdrMapResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_MAP)])
            ->getMock()
        ;

        $xdrMapResult = [
            $this->generateEntry('reserve', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 1000000000)),
            $this->generateEntry('project', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 10000000000)),
            $this->generateEntry('comission', 'getI128', XdrSCValType::SCV_I128,  new XdrInt128Parts(0, 100000000)),
        ];

        $xdrMapResultMock->method('getMap')->willReturn($xdrMapResult);

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $transactionResponseStub->expects($this->once())->method('getResultValue')->willReturn($xdrMapResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->once())->method('getCreatedAt')->willReturn('2025-04-15T23:15:41+00:00');

        return $transactionResponseStub;
    }

    private function loadContract(): Contract
    {
        $issuer         = EntityGenerator::createIssuer();
        $token          = EntityGenerator::createToken();
        $investor       = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract       = EntityGenerator::createActiveContract($issuer, $token);


        $this->persistor->persistAndFlush([
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract
        ]);

        return $contract;
    }
}
