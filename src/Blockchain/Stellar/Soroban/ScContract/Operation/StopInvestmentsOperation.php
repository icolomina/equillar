<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\StopInvestmentsOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Blockchain\Stellar\Soroban\Transaction\RegisterTransactionService;
use App\Entity\Investment\ContractInvestment;

class StopInvestmentsOperation
{
    /*public function __construct(
        private readonly StopInvestmentsOperationBuilder $stopDepositsOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
        private readonly RegisterTransactionService $registerTransactionService
    ){}

    public function stopInventments(ContractInvestment $contract): void
    {
        $invokeContractHostFunction = $this->stopDepositsOperationBuilder->build($contract);
        $transactionResponse = $this->processTransactionService->sendTransaction($invokeContractHostFunction, true);
        $resultValue = $transactionResponse->getResultValue();

       // $this->registerTransactionService->registerSuccessfulContractTransaction($contract->getAddress(), 'Investment', 'init', $transactionResponse);

        if($resultValue->getError()) {
            throw new \RuntimeException('Contract deposits cannot be stopped: ' . $resultValue->getError()->getCode()->getValue());
        }
        
    }*/
}
