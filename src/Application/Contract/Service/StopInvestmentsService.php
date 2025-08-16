<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractInvestmentsPauseResumeTransformer;
use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\StopInvestmentsOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractNames;
use App\Domain\Contract\ContractStatus;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\Contract;
use App\Persistence\PersistorInterface;

class StopInvestmentsService
{
    public function __construct(
        private readonly StopInvestmentsOperation $stopInvestmentsOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ContractInvestmentsPauseResumeTransformer $contractInvestmentsPauseResumeTransformer,
        private readonly PersistorInterface $persistor
    ){}

    public function stopInvestments(Contract $contract, ?string $reason = null): void
    {
        try{
            $transactionResponse = $this->stopInvestmentsOperation->stopInventments($contract);
            $trxLedger =  $transactionResponse->getLatestLedger() ?? $transactionResponse->getLedger();
            $trxResult = $this->scContractResultBuilder->getResultDataFromTransactionResponse($transactionResponse);
            
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contract->getAddress(),
                ContractNames::INVESTMENT->name,
                ContractFunctions::stop_investments->name,
                $trxResult,
                $transactionResponse->getTxHash(),
                $trxLedger
            );

            $contractInvestmentsPauseResume = $this->contractInvestmentsPauseResumeTransformer->fromContractStoppedInvestments($contract, $contractTransaction, 0, $reason);

            $contract->setStatus(ContractStatus::FUNDS_REACHED->name);
            $this->persistor->persist([$contractTransaction, $contractInvestmentsPauseResume, $contract]);
        }
        catch(TransactionExceptionInterface $ex) {
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contract->getAddress(),
                ContractNames::INVESTMENT->name,
                ContractFunctions::stop_investments->name,
                $ex->getError(),
                $ex->getHash(),
                $ex->getFailureLedger()
            );

            $this->persistor->persist([$contractTransaction]);
        }
        finally{
            $this->persistor->flush();
        }
    }
}