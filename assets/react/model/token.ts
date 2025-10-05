/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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