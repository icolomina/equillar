<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\User\DTO\Output;


use App\Domain\User\Portfolio\PortfolioResume;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

readonly class UserPortfolioOutput
{
    /**
     * @param UserContractDtoOutput[] $userContracts
     */
    public function __construct(
        public PortfolioResume $resume,
        public array $userContracts,
        public bool $isEmpty,
    ) {
    }
}
