<?php

namespace App\Blockchain\Stellar\Account;

use App\Stellar\Networks;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Responses\Account\AccountResponse;
use Soneso\StellarSDK\StellarSDK;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class StellarAccountLoader
{
    private ?KeyPair $keyPair = null;
    private ?AccountResponse $account  = null;

    public function __construct(
        private readonly string $stellarSecret
    ){
        $this->load(Networks::TESTNET);
    }

    public function load(Networks $network): void
    {
        $this->keyPair = KeyPair::fromSeed($this->stellarSecret);
        $this->account = ($network === Networks::TESTNET) 
            ? StellarSDK::getTestNetInstance()->requestAccount($this->keyPair->getAccountId())
            : StellarSDK::getPublicNetInstance()->requestAccount($this->keyPair->getAccountId())
        ;
    }

    public function getKeyPair(): KeyPair
    {
        return $this->keyPair;
    }

    public function getAccount(): AccountResponse
    {
        return $this->account;
    }
}
