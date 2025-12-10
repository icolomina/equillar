<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Blockchain\Stellar\Account;

use App\Application\SystemWallet\Service\RetrieveSystemWalletService;
use App\Domain\Crypt\Service\CryptedValueEncryptor;
use App\Domain\Token\Service\TokenNormalizer;
use App\Entity\Token;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\MuxedAccount;
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
        private readonly TokenNormalizer $tokenNormalizer
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

    public function getTokenBalance(Token $token): float 
    {
        $assetBalance = 0;
        $balances = $this->account->getBalances();

        if($balances->count() === 0) {
            return $assetBalance;
        }

        $balances->rewind();
        $found = false;

        while($balances->valid() && !$found ) {
            $currentBalance = $balances->current();

            if($currentBalance->getAssetCode() === $token->getCode() && $currentBalance->getAssetIssuer() === $token->getIssuerAddress()) {
                $assetBalance = ((float)$currentBalance->getBalance() == 0) 
                    ? 0 
                    : $this->tokenNormalizer
                        ->normalizeTokenValue($currentBalance->getBalance(), $token->getDecimals())
                        ->toPhp($token->getDecimals())
                ;

                $found = true;
            }

            $balances->next();
        }
        
        return $assetBalance;
    }

    /**
     * Muxed ID is mandatory so the account returned should be 'M .....'
     */
    public function generateMuxedAccount(int $muxedId): string
    {
        return (new MuxedAccount($this->account->getAccountId(), $muxedId))->getAccountId();
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
