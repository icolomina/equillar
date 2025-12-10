// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useMemo } from 'react';

export function useApiRoutes() {
  const apiBase = '/api/v1';

  return useMemo(
    () => ({
    
        getContracts:           `${apiBase}/contract/get-issuer-contracts`,
        getAvailableContracts:  `${apiBase}/contract/get-available-contracts`,
        createContract:         `${apiBase}/contract/create-contract`,
        createUserContractInvestment: `${apiBase}/user-contract-investment/create-user-investment`,
        getUserContracts:       `${apiBase}/user-contract-investment/get-user-contracts`,
        getUserPayments:        `${apiBase}/user-contract-payments/get-user-payments`,
        getUserPortfolio:       `${apiBase}/user/get-portfolio`,
        getAvailableTokens:     `${apiBase}/token/get-available-tokens`,
        getWithdrawalRequests:  `${apiBase}/contract/get-request-withdrawals`,
        getReserveFundContributions: `${apiBase}/contract/get-reserve-fund-contributions`,
        getContractBalanceMovements: `${apiBase}/contract/get-contract-balance-movements`,

    
        editContract:           (id: string|number) =>  `${apiBase}/contract/${id}/edit-contract`,
        modifyContract:         (id: string|number) =>  `${apiBase}/contract/${id}/modify-contract`,
        approveContract:        (id: string|number) =>  `${apiBase}/contract/${id}/approve-contract`,
        startContract:          (id: string|number) =>  `${apiBase}/contract/${id}/initalize-contract`,
        getContractDocument:    (id: string|number) =>  `${apiBase}/contract/${id}/get-contract-document`,
        getContractBalance:     (id: string|number) =>  `${apiBase}/contract/${id}/get-contract-token-balance`,
        requestWithdrawal:      (id: string|number) =>  `${apiBase}/contract/${id}/request-withdrawal`,
        approveWithdrawal:      (id: string|number) =>  `${apiBase}/contract/requested-withdrawal/${id}/approve`,
        pauseContract:          (id: string|number) =>  `${apiBase}/contract/${id}/pause-deposits`,
        resumeContract:         (id: string|number) =>  `${apiBase}/contract/${id}/resume-deposits`,

        // User Contract
        editUserContract:       (id: string|number) =>  `${apiBase}/user-contract-investment/${id}/edit-user-contract`,

        // Blockchain transaction
        getStellarTrxData:      (hash: string) => `${apiBase}/blockchain/stellar/get-tx-data?hash=${hash}`,

        checkReserveFundContribution: (id: number|string) => `${apiBase}/contract/reserve-fund-contribution/${id}/check`,
        transferReserveFundContributon: (id: number|string) => `${apiBase}/contract/reserve-fund-contribution/${id}/transfer`,

        requestAvailableToReserveFundMovement: (id: number|string) => `${apiBase}/contract/${id}/request-available-to-reserve-fund-movement`,
        moveAvailableToReserveFundMovement: (id: number|string) => `${apiBase}/contract/available-to-reserve-fund-movement/${id}/move`
  }), [apiBase]);
}
