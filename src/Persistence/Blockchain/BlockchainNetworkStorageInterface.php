<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Persistence\Blockchain;

use App\Entity\BlockchainNetwork;

interface BlockchainNetworkStorageInterface
{
    public function getByBlockchainAndNetwork(string $blockchain, string $network): ?BlockchainNetwork;
}
