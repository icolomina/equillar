<?php

namespace App\Application\Contract\Service\Blockchain;

use App\Application\Contract\Transformer\ContractInvestmentsPauseResumeTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractStopOrRestartInvestmentsOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\ContractStatus;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\Contract;
use App\Persistence\Contract\ContractBalanceStorageInterface;
use App\Persistence\PersistorInterface;
use App\Domain\Contract\ContractPauseOrResumeTypes;

class ContractStopOrRestartInvestmentsService
{
    public function __construct(
        private readonly ContractStopOrRestartInvestmentsOperation $contractStopOrRestartInvestmentsOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractInvestmentsPauseResumeTransformer $contractInvestmentsPauseResumeTransformer,
        private readonly ContractBalanceStorageInterface $contractBalanceStorage,
        private readonly PersistorInterface $persistor,
    ) {
    }

    public function stopOrRestartInvestments(Contract $contract, string $type, string $reason = 'No reason provided'): void
    {
        $lastContractBalance = $this->contractBalanceStorage->getLastBalanceByContract($contract);
        $currentFunds = $lastContractBalance?->getFundsReceived() ?? 0;

        $contractFunction = ($type === ContractPauseOrResumeTypes::PAUSE->name) ? ContractFunctions::stop_investments->name : ContractFunctions::restart_investments->name;

        try {
            $transactionResponse = $this->contractStopOrRestartInvestmentsOperation->stopOrRestartInventments($contract, $type);
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($transactionResponse);

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contract->getAddress(),
                ContractNames::INVESTMENT->name,
                $contractFunction,
                [$trxResult],
                $transactionResponse->getTxHash(),
                $transactionResponse->getCreatedAt()
            );

            $contractInvestmentsPauseResume = $this->contractInvestmentsPauseResumeTransformer->fromContractSuccessfulPausedOrResumedInvestments($contract, $contractTransaction, $currentFunds, $reason, $type);
            
            if($type === ContractPauseOrResumeTypes::PAUSE->name) {
                $contract->setStatus(ContractStatus::PAUSED->name);
                $contract->setLastPausedAt(new \DateTimeImmutable());
            }
            else{
                $contract->setStatus(ContractStatus::ACTIVE->name);
                $contract->setLastResumedAt(new \DateTimeImmutable());
            }

            $this->persistor->persist([$contractTransaction, $contractInvestmentsPauseResume, $contract]);
            
        } catch (TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contract->getAddress(),
                ContractNames::INVESTMENT->name,
                $contractFunction,
                $ex->getError(),
                $ex->getHash(),
                $ex->getCreatedAt()
            );

            $contractInvestmentsPauseResume = $this->contractInvestmentsPauseResumeTransformer->fromContractFailurePausedOrResumedInvestments($contract, $contractTransaction, $currentFunds, $reason, $type);

            $this->persistor->persist([$contractTransaction, $contractInvestmentsPauseResume]);
        } finally {
            $this->persistor->flush();
        }
    }
}
