// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

export interface Token {
    id: number
    enabled: boolean,
    name: string,
    code: string,
    createdAt: string,
    decimals: number,
    issuer: string,
    locale?: string,
    fiatReference?: string
}

export interface TokenContract {
    name: string,
    code: string,
    issuer: string,
    decimals: number,
    locale?: string,
    fiatReference?: string
}