<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Contract\Service;

use App\Entity\Contract\Contract;

class ContractImageUrlGenerator
{
    public function __construct(
        private readonly string $webserverEndpoint
    ){}

    public function getImageUrl(Contract $contract): string
    {
        return $this->webserverEndpoint . '/images/projects/'  . $contract->getImageName();
    }
}
