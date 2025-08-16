<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\ContractReserveFundContributionOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Entity\Contract\ContractReserveFundContribution;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ContractReserveFundContributionOperation
{
    public function __construct(
        private readonly ContractReserveFundContributionOperationBuilder $contractReserveFundContributionOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService
    ){}

    public function contributeToReserveFund(ContractReserveFundContribution $contractReserveFundContribution): GetTransactionResponse
    {
        $operation = $this->contractReserveFundContributionOperationBuilder->build($contractReserveFundContribution);
        return $this->processTransactionService->sendTransaction($operation, true);
    }
}
