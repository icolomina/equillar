<?php

namespace App\Presentation\UserContract\DTO\Input;

use Symfony\Component\Validator\Constraints\Date;

readonly class UserContractPaymentsInput
{
    public function __construct(
        #[Date(message: 'From must be a valid date')]
        public ?string $from = null,
        #[Date(message: 'To must be a valid date')]
        public ?string $to = null,
        public ?string $status = null,
        public ?int $projectId = null
    ){}
}
