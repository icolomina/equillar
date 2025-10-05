<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
namespace App\Blockchain\Stellar\Exception\Transaction;

interface TransactionExceptionInterface
{
    public function getStatus(): string;

    public function isSimulationFailure(): bool;

    public function getError(): string;

    public function getFailureLedger(): int;

    public function getHash(): ?string;
}
