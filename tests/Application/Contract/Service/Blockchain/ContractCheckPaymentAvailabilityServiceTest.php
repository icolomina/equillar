<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Tests\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\Blockchain\ContractCheckPaymentAvailabilityService;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\CheckContractPaymentAvailabilityOperation;
use App\Domain\Contract\ContractPaymentAvailabilityStatus;
use App\Domain\Contract\ContractStatus;
use App\Entity\Contract\ContractPaymentAvailability;
use App\Persistence\Contract\ContractPaymentAvailabilityStorageInterface;
use App\Persistence\Contract\ContractStorageInterface;
use App\Persistence\PersistorInterface;
use App\Tests\EntityGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Xdr\XdrInt128Parts;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Soneso\StellarSDK\Xdr\XdrSCValType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContractCheckPaymentAvailabilityServiceTest extends KernelTestCase
{
    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private ContractPaymentAvailabilityStorageInterface $contractPaymentAvailabilityStorage;
    private ContractStorageInterface $contractStorage;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->container = static::getContainer();
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->contractPaymentAvailabilityStorage = $this->container->get('test.App\Persistence\Contract\ContractPaymentAvailabilityStorageInterface');
        $this->contractStorage = $this->container->get('test.App\Persistence\Contract\ContractStorageInterface');
    }

    public function testCheckContractAvailabilityWithZeroRequiredFunds(): void
    {
        $transactionResponseStub = $this->createTransactionResponseStub(0);
        $checkOperationStub = $this->getMockBuilder(CheckContractPaymentAvailabilityOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $checkOperationStub->method('checkContractPaymentAvailability')->willReturn($transactionResponseStub);

        /**
         * @var ContractCheckPaymentAvailabilityService $serviceStub
         */
        $serviceStub = $this->getMockBuilder(ContractCheckPaymentAvailabilityService::class)
            ->setConstructorArgs([
                $checkOperationStub,
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractPaymentAvailabilityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractEntityTransformer'),
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->container->get('test.App\Domain\Utils\Math\I128Handler'),
                $this->persistor
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $contractPaymentAvailability = $this->loadContractPaymentAvailability();
        $serviceStub->checkContractAvailability($contractPaymentAvailability);

        $updatedPaymentAvailability = $this->contractPaymentAvailabilityStorage->getById($contractPaymentAvailability->getId());
        $contract = $this->contractStorage->getContractById($contractPaymentAvailability->getContract()->getId());

        $this->assertEquals(ContractPaymentAvailabilityStatus::PROCESSED->name, $updatedPaymentAvailability->getStatus());
        $this->assertEquals(0.0, $updatedPaymentAvailability->getRequiredFunds());
        $this->assertNotNull($updatedPaymentAvailability->getCheckedAt());
        $this->assertNotNull($updatedPaymentAvailability->getContractTransaction());
        $this->assertEquals(ContractStatus::ACTIVE->name, $contract->getStatus());
    }

    public function testCheckContractAvailabilityWithPositiveRequiredFunds(): void
    {
        $transactionResponseStub = $this->createTransactionResponseStub(5000000000); // 500 with 7 decimals
        $checkOperationStub = $this->getMockBuilder(CheckContractPaymentAvailabilityOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $checkOperationStub->method('checkContractPaymentAvailability')->willReturn($transactionResponseStub);

        /**
         * @var ContractCheckPaymentAvailabilityService $serviceStub
         */
        $serviceStub = $this->getMockBuilder(ContractCheckPaymentAvailabilityService::class)
            ->setConstructorArgs([
                $checkOperationStub,
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractPaymentAvailabilityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractEntityTransformer'),
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->container->get('test.App\Domain\Utils\Math\I128Handler'),
                $this->persistor
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $contractPaymentAvailability = $this->loadContractPaymentAvailability();
        $serviceStub->checkContractAvailability($contractPaymentAvailability);

        $updatedPaymentAvailability = $this->contractPaymentAvailabilityStorage->getById($contractPaymentAvailability->getId());
        $contract = $this->contractStorage->getContractById($contractPaymentAvailability->getContract()->getId());

        $this->assertEquals(ContractPaymentAvailabilityStatus::PROCESSED->name, $updatedPaymentAvailability->getStatus());
        $this->assertEquals(500.0, $updatedPaymentAvailability->getRequiredFunds());
        $this->assertNotNull($updatedPaymentAvailability->getCheckedAt());
        $this->assertNotNull($updatedPaymentAvailability->getContractTransaction());
        $this->assertEquals(ContractStatus::BLOCKED->name, $contract->getStatus());
    }

    private function createTransactionResponseStub(int $requiredFundsLo): MockObject
    {
        $xdrI128ResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_I128)])
            ->getMock()
        ;

        $xdrI128Parts = new XdrInt128Parts(0, $requiredFundsLo);
        $xdrI128ResultMock->method('getI128')->willReturn($xdrI128Parts);

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $transactionResponseStub->expects($this->once())->method('getResultValue')->willReturn($xdrI128ResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('aabbccdd11223344556677889900');
        $transactionResponseStub->expects($this->once())->method('getCreatedAt')->willReturn('2025-01-04T10:30:00+00:00');

        return $transactionResponseStub;
    }

    private function loadContractPaymentAvailability(): ContractPaymentAvailability
    {
        $organization = EntityGenerator::createOrganization();
        $issuer = EntityGenerator::createIssuer($organization);
        $token = EntityGenerator::createToken();
        $investor = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract = EntityGenerator::createActiveContract($issuer, $token);
        $contractPaymentAvailability = EntityGenerator::createContractPaymentAvailability($contract);

        $this->persistor->persistAndFlush([
            $organization,
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract,
            $contractPaymentAvailability
        ]);

        return $contractPaymentAvailability;
    }
}
