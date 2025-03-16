<?php

namespace App\Domain\Contract;

use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;

readonly class ContractOperationResult
{
    public function __construct(
        public GetTransactionResponse $getTransactionResponse,
        public mixed $result
    ){}
}
