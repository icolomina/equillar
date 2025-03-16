<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\ContractCallFunctionResultException;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractMultisigStatus;
use App\Domain\Contract\ContractNames;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\ContractInvestmentWithdrawalRequest;
use App\Persistence\PersistorInterface;

class ProcessWithdrawalRequestTransactionService
{
    public function __construct(
        private readonly ProcessTransactionService $processTransactionService,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly PersistorInterface $persistor
    ){}

    public function processWithdrawalRequest(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest): void
    {
        try {
            $trxResponse = $this->processTransactionService->waitForTransaction($contractInvestmentWithdrawalRequest->getHash(), ProcessTransactionService::MAX_ITERATIONS, 200);
            $trxResult   = $this->scContractResultBuilder->getResultData($trxResponse);

            if ($trxResult != ContractMultisigStatus::WAITING_FOR_SIGNATURES->value) {
                throw new ContractCallFunctionResultException('UNEXPECTED_RESULT', 'UNEXPECTED_RESULT', $trxResponse->getTxHash());
            }

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $contractInvestmentWithdrawalRequest->getContractInvestment()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::project_withdrawn->name,
                $trxResult,
                $trxResponse->getTxHash(),
                $trxResponse->getLatestLedger() ?? $trxResponse->getLedger()
            );

            $contractInvestmentWithdrawalRequest->setHash($trxResponse->getTxHash());
            $contractInvestmentWithdrawalRequest->setStatus($trxResponse->getStatus());
            $contractInvestmentWithdrawalRequest->setContractTransaction($contractTransaction);
        }
        catch(TransactionExceptionInterface $ex) {

            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $contractInvestmentWithdrawalRequest->getContractInvestment()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::project_withdrawn->name,
                $ex->getError(),
                $ex->getHash(),
                $ex->getFailureLedger()
            );

            $contractInvestmentWithdrawalRequest->setHash($ex->getHash());
            $contractInvestmentWithdrawalRequest->setStatus($ex->getStatus());
            $contractInvestmentWithdrawalRequest->setContractTransaction($contractTransaction);
        }
        finally{
            $this->persistor->persistAndFlush([$contractTransaction, $contractInvestmentWithdrawalRequest]);
        }

    }
}
