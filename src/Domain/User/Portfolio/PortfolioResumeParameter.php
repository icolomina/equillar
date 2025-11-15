<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\User\Portfolio;

class PortfolioResumeParameter implements \IteratorAggregate
{
    /**
     * @var array<string, float>
     */
    public array $data = [];

    public function __construct(
        public readonly string $portfolioParameter,
    ) {
    }

    public function increment(string $token, float $value): void
    {
        if (!isset($this->data[$token])) {
            $this->data[$token] = 0;
        }

        $this->data[$token] += $value;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }
}
