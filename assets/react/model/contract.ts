/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { TokenContract } from "./token";

export enum ContractStatus {
  APPROVED = 'APPROVED',
  ACTIVE = 'ACTIVE',
  REJECTED = 'REJECTED',
  REVIEWING = 'REVIEWING'
}

export interface ContracBalance {
  available: number,
  reserveFund: number,
  commision: number,
  fundsReceived: number,
  payments: number,
  projectWithdrawals: number,
  reserveFundContributions: number,
  percentajeFundsReceived: number,
  availableToReserveMovements: number
}

export interface ContractOutput {
  id: string;
  address: string | null;
  tokenContract: TokenContract,
  rate: number;
  createdAt: string;
  initializedAt: string|null;
  approvedAt: string|null;
  lastPausedAt: string|null;
  lastResumedAt: string|null;
  initialized: boolean;
  issuer: string;
  claimMonths: number;
  label: string;
  fundsReached: boolean;
  description: string,
  shortDescription: string,
  imageUrl: string,
  contractBalance: ContracBalance,
  status: string
  goal: number,
  minPerInvestment: number,
  returnType: string|null,
  percentageFundsReached: number
  returnMonths: number,
  projectAddress: string
}

export interface ContractWithdrawal {
  id: number,
  contractLabel: string;
  requestedAt: string;
  requestedBy: string;
  requestedAmount: number;
  status: string;
  approvedAt?: string;
  hash?: string;
}

export interface ContractReserveFundContributionRequestResult {
  contributionId: string,
  destinationAddress: string,
  amount: number
}

export interface ContractAvailableToReserveFundMovementCreatedResult {
  segmentFrom: string,
  segmentTo: string,
  status: string,
  amount: number
}

export interface ContractReserveFund {
  id: string,
  contractLabel: string,
  amount: number,
  status: string,
  createdAt: string,
  receivedAt: string,
  transferredAt: string,
  hash?: string
}

export interface ContractBalanceMovement {
  id: number;
  contractName: string;
  amount: number;
  segmentFrom: string;
  segmentTo: string;
  createdAt: string;
  movedAt: string | null;
  status: string;
  hash?: string;
}

export enum ContractReturnTypes {
  ReverseLoan = '1',
  Coupon = '2'
}

export const returnTypes = [
  {
    label: 'Choose a return type',
    value: 0
  },
  {
    label: 'Reverse Loan',
    value: ContractReturnTypes.ReverseLoan
  },
  {
    label: 'Coupon',
    value: ContractReturnTypes.Coupon
  }
]

export function getReturnType(value: string): number {
  if(value !== 'Reverse Loan' && value !== 'Coupon') {
    return 0;
  }

  return (value === 'Reverse Loan') ?  Number(ContractReturnTypes.ReverseLoan) : Number(ContractReturnTypes.Coupon);
}

