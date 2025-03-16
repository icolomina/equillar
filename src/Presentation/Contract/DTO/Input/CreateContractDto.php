<?php

namespace App\Presentation\Contract\DTO\Input;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateContractDto {

    private ?UploadedFile $file = null;

    public function __construct(
        #[NotBlank(message: 'Token cannot be empty')] 
        public readonly string $token,

        #[NotBlank(message: 'Rate cannot be empty')]
        public readonly string $rate,

        #[NotBlank(message: 'Months cannot be empty')]
        #[GreaterThan(0, message: 'Months must be greater than 0')]
        public readonly int|string $claimMonths,

        #[NotBlank(message: 'Label cannot be empty')]
        public readonly string $label,

        #[NotBlank(message: 'Goal cannot be empty')]
        #[GreaterThan(0, message: 'Goal must be greater than 0')]
        public readonly string|float $goal,

        #[NotBlank(message: 'Descrption cannot be empty')]
        public readonly ?string $shortDescription = null,

        #[NotBlank(message: 'Descrption cannot be empty')]
        public readonly ?string $description = null

    ){}

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }
}