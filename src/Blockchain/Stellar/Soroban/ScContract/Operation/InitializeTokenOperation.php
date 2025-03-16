<?php

namespace App\Blockchain\Stellar\Soroban\ScContract\Operation;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\InstallContractService;
use Soneso\StellarSDK\Soroban\Address;
use Soneso\StellarSDK\Xdr\XdrSCVal;

class InitializeTokenOperation
{
    public function __construct(
        private readonly InstallContractService $installContractService,
        private readonly StellarAccountLoader $stellarAccountLoader,
    ){}

    public function initializeTokenContract(string $tokenWasmId, string $tokenName, string $tokenSymbol, int $tokenDecimals): string
    {
        $stellarAdminAccountId = $this->stellarAccountLoader->getKeyPair()->getAccountId();
        return $this->installContractService->install($tokenWasmId, [
            Address::fromAccountId($stellarAdminAccountId)->toXdrSCVal(),
            XdrSCVal::forU32($tokenDecimals),
            XdrSCVal::forString($tokenName),
            XdrSCVal::forString($tokenSymbol)
        ]);
    }
}
