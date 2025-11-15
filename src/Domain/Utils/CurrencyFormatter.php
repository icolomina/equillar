<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\Utils;

class CurrencyFormatter
{
    private ?\NumberFormatter $numberFormatter = null;

    public function loadFormatter(string $locale): void
    {
        $this->numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
    }

    public function formatCurrency(float $amount, string $currencyIso): string
    {
        return $this->numberFormatter->formatCurrency($amount, $currencyIso);
    }
}
