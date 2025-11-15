<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
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
