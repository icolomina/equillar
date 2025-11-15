// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

export const GetContractsPath = '/api/v1/contract/get-issuer-contracts' ;
export const GetAvailableContractsPath = '/api/v1/contract/get-available-contracts';
export const CreateContractInvestmentPath = '/api/v1/contract/create-contract';
export const EditContractPath = '/api/v1/contract/%s/edit-contract';
export const ApproveContractPath = '/api/v1/contract/%s/approve-contract';
export const StartContractPath = '/api/v1/contract/%s/initalize-contract';
export const GetContractDocumentPath = '/api/v1/contract/%s/get-contract-document';
export const CreateUserContractInvestmentPath = '/api/v1/user-contract-investment/create-user-investment';
export const GetUserContractsInvestmentPath = '/api/v1/user-contract-investment/get-user-contracts';
export const GetUserContractPayments = '/api/v1/user-contract-payments/get-user-payments';
export const GetUserPortfolioPath = '/api/v1/user/get-portfolio';

export const GetContractTokenBalance = '/api/v1/contract/%s/get-contract-token-balance'
export const GetAvailableTokens = '/api/v1/token/get-available-tokens'
export const CreateRequestWithdrawalPath = '/api/v1/contract/%s/request-withdrawal';
export const GetContractWithdrawalRequestsPath = '/api/v1/contract/get-request-withdrawals';
export const ApproveContractRequestedWithdrawalPath = '/api/v1/contract/requested-withdrawal/%s/approve';

export const CreateReserveFundContributionPath = '/api/v1/contract/create-reserve-fund-contribution'
export const GetContractReserveFundContributionsPath = '/api/v1/contract/get-reserve-fund-contributions';
export const CheckReserveFundContributionPath = '/api/v1/contract/reserve-fund-contribution/%s/check';
export const MoveAvailableToReserveFundPath = '/api/v1/contract/move-balance-to-the-reserve';
export const CreateBalanceMovementPath = '/api/v1/contract/create-balance-movement';
export const GetBalanceMovementPath = '/api/v1/contract/get-balance-movements';
export const PauseContractInvestmentsPath = '/api/v1/contract/%s/pause-investments';
export const ResumeContractInvestmentsPath = '/api/v1/contract/%s/resume-investments';
