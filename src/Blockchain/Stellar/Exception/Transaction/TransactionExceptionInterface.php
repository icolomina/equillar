<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Blockchain\Stellar\Exception\Transaction;

interface TransactionExceptionInterface
{
    public function getStatus(): string;

    public function isSimulationFailure(): bool;

    public function getError(): string;

    public function getFailureLedger(): int;

    public function getHash(): ?string;

    public function getCreatedAt(): ?string;
}
