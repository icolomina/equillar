<?php

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Application\Contract\Transformer\ContractWithdrawalApprovementEntityTransformer;
use App\Blockchain\Stellar\Exception\Transaction\ContractCallFunctionResultException;
use App\Blockchain\Stellar\Exception\Transaction\TransactionExceptionInterface;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\Investment\ProjectWithdrawalOperation;
use App\Domain\Contract\ContractFunctions;
use App\Domain\Contract\ContractMultisigStatus;
use App\Domain\Contract\ContractNames;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\ContractInvestmentWithdrawalRequest;
use App\Persistence\PersistorInterface;

class ProjectWithdrawalService
{
    public function __construct(
        private readonly ProjectWithdrawalOperation $projectWithdrawalOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,    
        private readonly ContractWithdrawalApprovementEntityTransformer $contractWithdrawalApprovementEntityTransformer,
        private readonly PersistorInterface $persistor
    ){}

    public function approveProjectWithdrawal(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest)
    {
        $contractTransaction = null;
        $contractInvestmentWithdrawalApprovement = null;

        try{
            $trxResponse = $this->projectWithdrawalOperation->projectWithdrawn($contractInvestmentWithdrawalRequest->getContractInvestment(), $contractInvestmentWithdrawalRequest->getRequestedAmount());
            $trxResult = $this->scContractResultBuilder->getResultData($trxResponse);

            if ($trxResult != ContractMultisigStatus::COMPLETED->value) {
                throw new ContractCallFunctionResultException('UNEXPECTED_RESULT', 'UNEXPECTED_RESULT', $trxResponse->getTxHash());
            }

            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $$contractInvestmentWithdrawalRequest->getContractInvestment()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::project_withdrawn->name,
                $trxResult,
                $trxResponse->getTxHash(),
                $trxResponse->getLatestLedger() ?? $trxResponse->getLedger()
            );

            $contractInvestmentWithdrawalApprovement = $this->contractWithdrawalApprovementEntityTransformer->fromRequestApprovedToEntity(
                $contractInvestmentWithdrawalRequest, 
                $contractTransaction,
                $trxResponse->getStatus()
            );
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

            $contractInvestmentWithdrawalApprovement = $this->contractWithdrawalApprovementEntityTransformer->fromRequestApprovementFailureToEntity(
                $contractInvestmentWithdrawalRequest, 
                $contractTransaction,
                $ex->getStatus()
            );
        }
        finally{
            $this->persistor->persistAndFlush([$contractTransaction, $contractInvestmentWithdrawalApprovement]);
        }
    }
}
