// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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
