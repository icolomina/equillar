<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Domain\UserContract;

enum UserContractStatus: int
{
    case BLOCKED = 1;
    case CLAIMABLE = 2;
    case WAITING_FOR_PAYMENT = 3;
    case CASH_FLOWING = 4;
    case FINISHED = 5;
    case UNKNOWN = 6;
}
