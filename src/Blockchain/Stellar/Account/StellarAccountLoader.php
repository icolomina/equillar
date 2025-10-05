<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Account;

use App\Application\SystemWallet\Service\RetrieveSystemWalletService;
use App\Domain\Crypt\Service\CryptedValueEncryptor;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\Responses\Account\AccountResponse;
use Soneso\StellarSDK\StellarSDK;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

#[Lazy]
class StellarAccountLoader
{
    private ?KeyPair $keyPair = null;
    private ?StellarSDK $sdk = null;
    private ?AccountResponse $account = null;
    private ?Network $network = null;

    public function __construct(
        private readonly RetrieveSystemWalletService $retrieveSystemWalletService,
        private readonly CryptedValueEncryptor $cryptedValueEncryptor,
    ) {
        $this->load();
    }

    public function load(): void
    {
        $systemWalletData = $this->retrieveSystemWalletService->retrieve();
        $secret = $this->cryptedValueEncryptor->getSecret($systemWalletData->cryptedValue);

        $this->keyPair = KeyPair::fromSeed($secret);
        $this->sdk = ($systemWalletData->isTest)
            ? StellarSDK::getTestNetInstance()
            : StellarSDK::getPublicNetInstance()
        ;

        $this->network = ($systemWalletData->isTest) ? Network::testnet() : Network::public();
        $this->account = $this->sdk->requestAccount($this->keyPair->getAccountId());
    }

    public function getKeyPair(): KeyPair
    {
        return $this->keyPair;
    }

    public function getAccount(bool $forceReload = false): AccountResponse
    {
        if($forceReload) {
            $this->account = $this->sdk->requestAccount($this->keyPair->getAccountId());
        }
        
        return $this->account;
    }

    public function getSdk(): StellarSDK
    {
        return $this->sdk;
    }

    public function getNetwork(): Network
    {
        return $this->network;
    }
}
