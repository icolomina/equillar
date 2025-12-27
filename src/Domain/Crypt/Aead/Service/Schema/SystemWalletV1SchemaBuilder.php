<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Crypt\Aead\Service\Schema;

use App\Domain\Crypt\Aead\EntitySchemaBuilderInterface;
use App\Entity\SystemWallet;

class SystemWalletV1SchemaBuilder implements EntitySchemaBuilderInterface
{
    /**
     * @param SystemWallet $systemWallet
     */
    public function build(object $systemWallet): string
    {
        $adData = [
            'address'    => $systemWallet->getAddress(),
            'blockchain' => $systemWallet->getBlockchainNetwork()->getLabel(),
            'timestamp'  => $systemWallet->getCreatedAt()->getTimestamp(),
        ];

        ksort($adData);
        return json_encode($adData);
    }

    public function getEntityClass(): string
    {
        return SystemWallet::class;
    }

    public function getVersion(): string
    {
        return 'v1';
    }

}