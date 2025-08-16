<?php

namespace App\Presentation\Contract\DTO\Output;

readonly class ContractReserveFundContributionDtoOutput
{
    public function __construct(
        public int    $id,
        public string $contractLabel,
        public float  $amount,
        public string $status,
        public string $createdAt,
        public string $receivedAt,
        public string $transferredAt
    ){}
}
