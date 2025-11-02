/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

export enum BlockchainErrorType {
    CONTRACT_EXECUTION_FAILED = 'CONTRACT_EXECUTION_FAILED',
    BLOCKCHAIN_NETWORK_ERROR = 'BLOCKCHAIN_NETWORK_ERROR'
}

export interface BlockchainErrorResponse {
    error: BlockchainErrorType;
    message: string;
    contract_id?: string | number;
    transaction_hash?: string;
}

export interface BlockchainError extends BlockchainErrorResponse {
    timestamp: number;
}
