// src/hooks/useApiPaths.ts
import { useMemo } from 'react';

export function useApiRoutes() {
  const apiBase = '/api/v1';

  return useMemo(
    () => ({
    
        getContracts:           `${apiBase}/contract/get-issuer-contracts`,
        getAvailableContracts:  `${apiBase}/contract/get-available-contracts`,
        createContract:         `${apiBase}/contract/create-contract`,
        getUserContracts:       `${apiBase}/user-contract-investment/get-user-contracts`,
        getUserPayments:        `${apiBase}/user-contract-payments/get-user-payments`,
        getUserPortfolio:       `${apiBase}/user/get-portfolio`,
        getAvailableTokens:     `${apiBase}/token/get-available-tokens`,
        getWithdrawalRequests:  `${apiBase}/contract/get-request-withdrawals`,
        getReserveFundContributions: `${apiBase}/contract/get-reserve-fund-contributions`,

    
        editContract:           (id: string|number) =>  `${apiBase}/contract/${id}/edit-contract`,
        modifyContract:         (id: string|number) =>  `${apiBase}/contract/${id}/modify-contract`,
        approveContract:        (id: string|number) =>  `${apiBase}/contract/${id}/approve-contract`,
        startContract:          (id: string|number) =>  `${apiBase}/contract/${id}/initalize-contract`,
        getContractDocument:    (id: string|number) =>  `${apiBase}/contract/${id}/get-contract-document`,
        getContractBalance:     (id: string|number) =>  `${apiBase}/contract/${id}/get-contract-token-balance`,
        requestWithdrawal:      (id: string|number) =>  `${apiBase}/contract/${id}/request-withdrawal`,
        approveWithdrawal:      (id: string|number) =>  `${apiBase}/contract/requested-withdrawal/${id}/approve`,

        // User Contract
        editUserContract:       (id: string|number) =>  `${apiBase}/user-contract-investment/${id}/edit-user-contract`,

        // Blockchain transaction
        getStellarTrxData:      (hash: string) => `${apiBase}/blockchain/stellar/get-tx-data?hash=${hash}`,

        requestReserveFundContribution: (id: string|number) =>  `${apiBase}/contract/${id}/request-reserve-fund-contribution`,
  }), [apiBase]);
}
