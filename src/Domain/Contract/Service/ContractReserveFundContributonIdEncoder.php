<?php

namespace App\Domain\Contract\Service;

use Symfony\Component\Uid\Uuid;

class ContractReserveFundContributonIdEncoder
{
    public function encodeId(string $uuid): string
    {
        if(!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Invalid Uuid');
        }

        return Uuid::fromString($uuid)->toBase32();
    }

    public function decodeId(string $id): string
    {
        return Uuid::fromBase32($id);
    }
}
