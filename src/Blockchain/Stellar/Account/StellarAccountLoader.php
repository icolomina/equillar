<?php

namespace App\Blockchain\Stellar\Account;

use App\Application\SystemWallet\Service\RetrieveSystemWalletService;
use App\Domain\Crypt\Service\CryptedValueEncryptor;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Responses\Account\AccountResponse;
use Soneso\StellarSDK\StellarSDK;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class StellarAccountLoader
{
    private ?KeyPair $keyPair = null;
    private ?StellarSDK $sdk = null;
    private ?AccountResponse $account  = null;

    public function __construct(
        private readonly RetrieveSystemWalletService $retrieveSystemWalletService,
        private readonly CryptedValueEncryptor $cryptedValueEncryptor
    ){ 
        $this->load();
    }

    public function load(): void
    {
        $systemWalletData = $this->retrieveSystemWalletService->retrieve();
        $secret           = $this->cryptedValueEncryptor->getSecret($systemWalletData->cryptedValue);

        $this->keyPair = KeyPair::fromSeed($secret);
        $this->sdk     = ($systemWalletData->isTest) 
            ? StellarSDK::getTestNetInstance() 
            : StellarSDK::getPublicNetInstance()
        ;

        $this->account = $this->sdk->requestAccount($this->keyPair->getAccountId());
    }

    public function getKeyPair(): KeyPair
    {
        return $this->keyPair;
    }

    public function getAccount(): AccountResponse
    {
        return $this->account;
    }

    public function getSdk(): StellarSDK
    {
        return $this->sdk;
    }
}
