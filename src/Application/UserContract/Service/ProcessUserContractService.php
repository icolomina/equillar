<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\UserContract\Service;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Application\UserContract\Mapper\UserInvestmentTrxResultMapper;
use App\Blockchain\Stellar\Exception\Transaction\GetTransactionException;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\UserContract;
use App\Message\CheckContractBalanceMessage;
use App\Persistence\PersistorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ProcessUserContractService
{
    public function __construct(
        private readonly ProcessTransactionService $processTransactionService,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly PersistorInterface $persistor,
        private readonly UserInvestmentTrxResultMapper $userInvestmentTrxResultMapper,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function processUserContractTransaction(UserContract $userContract): void
    {
        $contractTransaction = null;

        try {
            $transactionResponse = $this->processTransactionService->waitForTransaction($userContract->getHash());
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($transactionResponse);
            $this->userInvestmentTrxResultMapper->mapToEntity($trxResult, $userContract);
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $userContract->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::invest->name,
                $trxResult,
                $transactionResponse->getTxHash(),
                $transactionResponse->getCreatedAt()
            );

            $this->bus->dispatch(new CheckContractBalanceMessage($userContract->getContract()->getId(), $transactionResponse->getLedger()));
        } catch (GetTransactionException $ex) {
            $userContract->setStatus($ex->getStatus());
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $userContract->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::invest->name,
                $ex
            );
        } finally {
            $this->persistor->persistAndFlush([$userContract, $contractTransaction]);
        }
    }
}
