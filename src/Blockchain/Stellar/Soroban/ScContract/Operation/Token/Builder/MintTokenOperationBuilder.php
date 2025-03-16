<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation\Token\Builder;

use App\Domain\Contract\ContractFunctions;
use App\Domain\I128;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Token;
use Soneso\StellarSDK\InvokeContractHostFunction;
use Soneso\StellarSDK\InvokeHostFunctionOperation;
use Soneso\StellarSDK\InvokeHostFunctionOperationBuilder;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class MintTokenOperationBuilder
{
    public function __construct(
        private readonly TokenNormalizer $tokenNormalizer
    ){ }

    public function build(Token $token, string $addressToMint, string $amount): InvokeHostFunctionOperation
    {
        $normalizedValue = $this->tokenNormalizer->normalizeTokenValue((string)$amount, $token->getDecimals());
        $valueInI128 = new I128((int)$normalizedValue);

        $invokeContractHostFunction = new InvokeContractHostFunction($token->getAddress(), ContractFunctions::mint->name, [
            Address::fromAccountId($addressToMint)->toXdrSCVal(),
            XdrSCVal::forI128Parts($valueInI128->getLo(), $valueInI128->getHi()),
        ]);

        $builder = new InvokeHostFunctionOperationBuilder($invokeContractHostFunction);
        return $builder->build();
    }
}
