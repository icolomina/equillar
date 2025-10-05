<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace App\Application\Token\Transformer;

use App\Domain\DateFormats;
use App\Entity\Token;
use App\Presentation\Token\DTO\Output\TokenContractDtoOutput;
use App\Presentation\Token\DTO\Output\TokenDtoOutput;

class TokenEntityTransformer
{
    public function fromEntityToOutputDto(Token $token): TokenDtoOutput
    {
        return new TokenDtoOutput(
            (string) $token->getId(),
            $token->getName(),
            $token->getCode(),
            $token->getAddress(),
            $token->getCreatedAt()->format(DateFormats::OUTPUT_DATE_FORMAT->value),
            $token->isEnabled(),
            $token->getIssuer(),
            $token->getDecimals(),
            $token->getLocale(),
            $token->getReferencedCurrency()
        );
    }

    public function fromEntityToContractTokenOutputDto(Token $token): TokenContractDtoOutput
    {
        return new TokenContractDtoOutput(
            $token->getName(),
            $token->getCode(),
            $token->getIssuer(),
            $token->getDecimals(),
            $token->getLocale(),
            $token->getReferencedCurrency()
        );
    }

    /**
     * @param Token[] $rows
     *
     * @return TokenDtoOutput[]
     */
    public function fromEntitiesToOutputDtos(iterable $rows): iterable
    {
        return array_map(
            fn (Token $token) => $this->fromEntityToOutputDto($token),
            $rows
        );
    }
}
