<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Builder\ClaimOperationBuilder;
use App\Blockchain\Stellar\Soroban\Transaction\ProcessTransactionService;
use App\Blockchain\Stellar\Token\TokenNormalizerService;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Investment\UserContractInvestment;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

class ClaimOperation
{
    public function __construct(
        private readonly ClaimOperationBuilder $claimOperationBuilder,
        private readonly ProcessTransactionService $processTransactionService,
        private readonly TokenNormalizerService $tokenNormalizerService,
        private readonly ScContractResultBuilder $scContractResultBuilder,
    ){}

    public function claim(UserContractInvestment $userContractInvestment): GetTransactionResponse
    {
        $operation = $this->claimOperationBuilder->build($userContractInvestment);
        return $this->processTransactionService->sendTransaction($operation, true);
    }
}
