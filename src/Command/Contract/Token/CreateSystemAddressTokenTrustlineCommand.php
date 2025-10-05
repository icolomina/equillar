<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Command\Contract\Token;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Persistence\Token\TokenStorageInterface;
use Soneso\StellarSDK\AssetTypeCreditAlphanum4;
use Soneso\StellarSDK\ChangeTrustOperationBuilder;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\TransactionBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:system-address:create-token-trustline'
)]
class CreateSystemAddressTokenTrustlineCommand
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly StellarAccountLoader $stellarAccountLoader
    ){}

    public function __invoke(
        SymfonyStyle $io, #[Option] ?string $token = null
    ): int {

        $token = $this->tokenStorage->getOneByCode($token);
        $stellarAsset = new AssetTypeCreditAlphanum4($token->getCode(), $token->getIssuerAddress());

        $cto = (new ChangeTrustOperationBuilder($stellarAsset))->build();
        $transaction = (new TransactionBuilder($this->stellarAccountLoader->getAccount()))->addOperation($cto)->build();
        $transaction->sign($this->stellarAccountLoader->getKeyPair(), $this->stellarAccountLoader->getNetwork());

        $transactionResponse = $this->stellarAccountLoader->getSdk()->submitTransaction($transaction);

        if(!$transactionResponse->isSuccessful()) {
            $io->writeln('Transaction failed');
            return Command::INVALID;
        }

        $trustorBalances = $this->stellarAccountLoader->getAccount(true)->getBalances();
        $trustorBalances->rewind();
        $trustlineFound  = false;

        while($trustorBalances->valid() && !$trustlineFound) {
            $currentBalance = $trustorBalances->current();
            if ($currentBalance->getAssetCode() == $token->getCode()) {
                $io->writeln("Trustline for " . $token->getCode() . " found. Limit: " . $currentBalance->getLimit());
                $trustlineFound = true;
            }

            $trustorBalances->next();
        }

        if(!$trustlineFound) {
            $io->writeln('The trustline seems to be created but it has not been found in the trustor balances');
        }

        return Command::SUCCESS;
        
    }


}
