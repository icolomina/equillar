<?php

namespace App\Command\Contract\Token;

use App\Domain\Token\Service\TokenNormalizer;
use App\Persistence\Token\TokenStorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:token:normalize-value'
)]
class TokenNormalizerCommand
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TokenNormalizer $tokenNormalizer
    ){}

    public function __invoke(
        SymfonyStyle $io, #[Option] ?string $token = null, #[Option] ?string $value = null
    ): int {
        $token = $this->tokenStorage->getOneByCode($token);
        $normalizedValue = $this->tokenNormalizer->normalizeTokenValue($value, $token->getDecimals());

        $io->writeln('Hi value: ' . $normalizedValue->getHi());
        $io->writeln('Lo value: ' . $normalizedValue->getLo());

        return Command::SUCCESS;
    }

}
