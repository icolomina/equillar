// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

export interface StellarTransaction {
    isSuccessful: boolean,
    ledger: number,
    feeCharged: string,
    hash: string
}