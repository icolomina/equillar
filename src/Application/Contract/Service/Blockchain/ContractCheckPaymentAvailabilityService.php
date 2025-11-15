<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractEntityTransformer;
use App\Application\Contract\Transformer\ContractPaymentAvailabilityTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\CheckContractPaymentAvailabilityOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\Exception\ContractExecutionFailedException;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Domain\Utils\Math\I128Handler;
use App\Entity\Contract\ContractPaymentAvailability;
use App\Persistence\PersistorInterface;

class ContractCheckPaymentAvailabilityService
{
    public function __construct(
        private readonly CheckContractPaymentAvailabilityOperation $checkContractPaymentAvailabilityOperation,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractPaymentAvailabilityTransformer $contractPaymentAvailabilityTransformer,
        private readonly ContractEntityTransformer $contractEntityTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly I128Handler $i128Handler,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function checkContractAvailability(ContractPaymentAvailability $contractPaymentAvailability): void
    {
        $contractTransaction = null;

        try {
            $trxResponse = $this->checkContractPaymentAvailabilityOperation->checkContractPaymentAvailability($contractPaymentAvailability);
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($trxResponse);
            $requiredFunds = $this->i128Handler->fromI128ToPhpFloat($trxResult->getLo(), $trxResult->getHi(), $contractPaymentAvailability->getContract()->getToken()->getDecimals());

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractPaymentAvailability->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::check_reserve->name,
                $trxResult,
                $trxResponse->getTxHash(),
                $trxResponse->getCreatedAt()
            );

            $this->contractPaymentAvailabilityTransformer->updateContractPaymentAvalabilityAsProcessed($contractPaymentAvailability, $contractTransaction, $requiredFunds);
            $this->persistor->persist([$contractTransaction, $contractPaymentAvailability]);
            if ($requiredFunds > 0) {
                $contract = $contractPaymentAvailability->getContract();
                $this->contractEntityTransformer->updateContractAsBlocked($contract);
                $this->persistor->persist($contract);
            }

            $this->persistor->flush();
        } catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contractPaymentAvailability->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::check_reserve->name,
                $ex
            );

            $this->contractPaymentAvailabilityTransformer->updateContractPaymentAvalabilityAsFailed($contractPaymentAvailability, $contractTransaction);
            $this->persistor->persistAndFlush([$contractTransaction, $contractPaymentAvailability]);
            
            throw ContractExecutionFailedException::fromContractTransaction($contractTransaction);
        }
    }
}
