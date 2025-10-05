<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Transaction;

use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Blockchain\Stellar\TransactionData;

class GetStellarTransactionDataService
{
    public const STROOP_SCALE = 10000000;
    public const TOKEN_NATIVE = 'XLM';

    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
    ) {
    }

    public function getTransactionData(string $txHash): TransactionData
    {
        $sdk = $this->stellarAccountLoader->getSdk();
        $txResponse = $sdk
            ->transactions()
            ->transaction($txHash)
        ;

        $feeCharged = (float) $txResponse->getFeeCharged() / self::STROOP_SCALE;

        return new TransactionData(
            $txResponse->isSuccessful(),
            $txResponse->getLedger(),
            (string) $feeCharged.' '.self::TOKEN_NATIVE,
            $txResponse->getHash()
        );
    }
}
