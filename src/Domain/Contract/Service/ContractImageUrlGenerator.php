<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */
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
