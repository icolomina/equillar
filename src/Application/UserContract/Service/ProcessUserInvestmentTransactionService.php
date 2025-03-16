<?php

namespace App\Application\UserContract\Service;

use App\Application\Contract\Transformer\ContractTransactionEntityTransformer;
use App\Application\UserContract\Mapper\UserInvestmentTrxResultMapper;
use App\Blockchain\Stellar\Exception\Transaction\GetTransactionException;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Domain\Contract\ContractFunctions;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\UserContractInvestment;
use App\Persistence\PersistorInterface;
use App\Domain\Contract\ContractNames;

class ProcessUserInvestmentTransactionService
{
    public function __construct(
        private readonly ProcessTransactionService $processTransactionService,
        private readonly ContractTransactionEntityTransformer $contractTransactionEntityTransformer,
        private readonly ScContractResultBuilder $scContractResultBuilder,
        private readonly PersistorInterface $persistor,
        private readonly UserInvestmentTrxResultMapper $userInvestmentTrxResultMapper
    ){}

    public function processUserInvestmentTransaction(UserContractInvestment $userContractInvestment): void
    {
        try{
            $transactionResponse = $this->processTransactionService->waitForTransaction($userContractInvestment->getHash(), ProcessTransactionService::MAX_ITERATIONS, 200);
            $trxResult = $this->scContractResultBuilder->getResultData($transactionResponse);
            $this->userInvestmentTrxResultMapper->mapToEntity($trxResult, $userContractInvestment);
            $contractTransaction = $this->contractTransactionEntityTransformer->fromSuccessfulTransaction(
                $userContractInvestment->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::invest->name,
                $trxResult,
                $transactionResponse->getTxHash(),
                $transactionResponse->getLatestLedger() ?? $transactionResponse->getLedger()
            );
        }
        catch(GetTransactionException $ex){
            $userContractInvestment->setStatus($ex->getStatus());
            $contractTransaction = $this->contractTransactionEntityTransformer->fromFailedTransaction(
                $userContractInvestment->getContract()->getAddress(),
                ContractNames::INVESTMENT->value,
                ContractFunctions::invest->name,
                $ex->getError(),
                $ex->getHash(),
                $ex->getFailureLedger()
            );
        }
        finally {
            $this->persistor->persistAndFlush([$userContractInvestment, $contractTransaction]);
        }
    }
}
    
