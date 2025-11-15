<?php

// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.
namespace App\Presentation\Contract\DTO\Input;

use App\Domain\Contract\ContractReturnType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CreateContractDto
{
    private ?UploadedFile $file = null;

    public function __construct(
        #[NotBlank(message: 'Token cannot be empty')]
        public readonly string $token,

        #[NotBlank(message: 'Rate cannot be empty')]
        public readonly string $rate,

        #[NotBlank(message: 'Goal cannot be empty')]
        #[GreaterThanOrEqual(0, message: 'Goal must be greater than or equal 0')]
        public readonly int|string $claimMonths,

        #[NotBlank(message: 'Label cannot be empty')]
        public readonly string $label,

        #[NotBlank(message: 'Goal cannot be empty')]
        #[GreaterThan(0, message: 'Goal must be greater than 0')]
        public readonly string|float|int $goal,

        #[NotBlank(message: 'Short Description cannot be empty')]
        public readonly string $shortDescription,

        #[NotBlank(message: 'Descrption cannot be empty')]
        public readonly string $description,

        #[NotBlank(message: 'Min per investment cannot be empty')]
        #[GreaterThan(0, message: 'Min per investment must be greater than 0')]
        public readonly string|float|int $minPerInvestment,

        #[NotBlank(message: 'Project Address cannot be empty')]
        public readonly string $projectAddress,

        #[NotBlank(message: 'Return type cannot be empty')]
        #[Choice(choices: [ContractReturnType::COUPON->value, ContractReturnType::REVERSE_LOAN->value], message: 'Choose a valid return type')]
        public readonly string|int $returnType,

        #[NotBlank(message: 'Return months cannot be empty')]
        public readonly string|int $returnMonths,
    ) {
    }

    #[Callback]
    public function validateGoal(ExecutionContextInterface $context, mixed $payload): void
    {
        if (!is_numeric($this->goal)) {
            $context
                ->buildViolation('The fundraising goal must be numeric')
                ->atPath('goal')
                ->addViolation()
            ;
        }

        if ((float) $this->goal <= 0) {
            $context
                ->buildViolation('The fundraising goal must be greater than 0')
                ->atPath('goal')
                ->addViolation()
            ;
        }
    }

    #[Callback]
    public function validateMinPerInvestment(ExecutionContextInterface $context, mixed $payload): void
    {
        if (!is_numeric($this->minPerInvestment)) {
            $context
                ->buildViolation('The min per investment value must be numeric')
                ->atPath('minPerInvestment')
                ->addViolation()
            ;
        }

        if ((float) $this->goal <= 0) {
            $context
                ->buildViolation('The min per investment value must be greater than 0')
                ->atPath('minPerInvestment')
                ->addViolation()
            ;
        }
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }
}
