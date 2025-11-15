<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\Blockchain\ContractActivationService;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractActivationOperation;
use App\Entity\Contract\Contract;
use App\Persistence\Contract\ContractStorageInterface;
use App\Persistence\PersistorInterface;
use App\Tests\EntityGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContractActivationServiceTest extends KernelTestCase
{

    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private ContractStorageInterface $contractStorage;

    protected function setUp(): void
    {
        parent::setUp(); 

        self::bootKernel(); 
        $this->container = static::getContainer(); 
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->contractStorage = $this->container->get('test.App\Persistence\Contract\ContractStorageInterface');
    }

    public function testCreateContractSuccess(): void
    {
        $transactionResponseStub = $this->createTransactionResponseStub();
        $contractActivationOperationStub = $this->getMockBuilder(ContractActivationOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $contractActivationOperationStub->method('activateContract')->willReturn($transactionResponseStub);

        /**
         * @var ContractActivationService $contractActivationServiceStub
         */
        $contractActivationServiceStub = $this->getMockBuilder(ContractActivationService::class)
            ->setConstructorArgs([
                $contractActivationOperationStub,
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractEntityTransformer'),
                $this->persistor
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $contract = $this->loadContract();
        $contractActivationServiceStub->activateContract($contract);
        $contract = $this->contractStorage->getContractById($contract->getId());

        $this->assertEquals('778585757uj4h4743h377f7f64g4hb', $contract->getAddress());
    }

    private function createTransactionResponseStub(): MockObject
    {
        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $transactionResponseStub->expects($this->once())->method('getCreatedContractId')->willReturn('778585757uj4h4743h377f7f64g4hb');
        $transactionResponseStub->expects($this->once())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->once())->method('getCreatedAt')->willReturn('1762109943');

        return $transactionResponseStub;
    }

    private function loadContract(): Contract
    {
        $issuer         = EntityGenerator::createIssuer();
        $token          = EntityGenerator::createToken();
        $investor       = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract       = EntityGenerator::createApprovedContract($issuer, $token);


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
