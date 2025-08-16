<?php

namespace App\Application\Token\Service;

use App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\GetBalanceOperation;
use App\Domain\I128;
use App\Domain\ScContract\Service\ScContractResultBuilder;
use App\Entity\Contract\Contract;

class GetTokenBalanceService
{
    public function __construct(
        private readonly GetBalanceOperation $getBalanceOperation,
        private readonly ScContractResultBuilder $scContractResultBuilder
    ){}

    public function getContractTokenBalance(Contract $contract, string $address): float
    {
        $trxResponse = $this->getBalanceOperation->getTokenBalance($contract->getToken(), $address);
        $result      = $this->scContractResultBuilder->getResultDataFromTransactionResponse($trxResponse);
        return I128::fromLoAndHi($result->lo, $result->hi)->toPhp($contract->getToken()->getDecimals());
        
    }
}
