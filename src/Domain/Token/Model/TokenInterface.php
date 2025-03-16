<?php

namespace App\Domain\Token\Model;

interface TokenInterface
{
    public function getName(): string;
    public function getCode(): string;
    public function getAddress(): string;
    public function isEnabled(): bool;
    public function getDecimals(): int;
    public function getIssuer(): string;
}
