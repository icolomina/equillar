// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { TokenContract } from "./token"

export interface UserContractInvestment {
    id: string,
    contractIssuer: string,
    contractLabel: string,
    tokenContract: TokenContract,
    rate: number,
    withdrawalDate: string,
    deposited: number,
    interest: number,
    commission: number,
    total: number,
    hash: string,
    status: string,
    paymentType: string,
    paymentsCalendar?: UserContractCalendarItem[]
}

export interface UserContractCalendarItem {
    date: string,
    value: number,
    isTransferred: boolean
    transferredAt?: string
    willBeTransferredAt?: string
}

export interface UserContractPayment {
    id: number,
    projectIssuer: string,
    projectName: string,
    hash?: string,
    paymentEmittedAt: string,
    totalToReceive: string,
    status: string,
    paymentPaidAt?: string,
    totalReceived?: string
}

export interface UserPortfolio {
    resume: UserPortfolioResume
    userContracts: UserContractInvestment[],
    isEmpty: boolean
}

export type UserPortfolioResumeParameters = {
    [key: string]: string;
}

export interface UserPortfolioResume {
    depositInfo: UserPortfolioResumeParameters,
    interestsInfo: UserPortfolioResumeParameters,
    totalInfo: UserPortfolioResumeParameters,
    totalChargedInfo: UserPortfolioResumeParameters,
    totalPendingToChargeInfo: UserPortfolioResumeParameters,
    totalClaimableInfo: UserPortfolioResumeParameters,
}