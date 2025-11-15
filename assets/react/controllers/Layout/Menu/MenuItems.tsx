// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import DashboardIcon from '@mui/icons-material/Dashboard';
import AccountBalanceWalletIcon from '@mui/icons-material/AccountBalanceWallet';
import MonetizationOnIcon from '@mui/icons-material/MonetizationOn';
import PaymentIcon from '@mui/icons-material/Payment';
import PortfolioIcon from '@mui/icons-material/Work';
import CurrencyExchangeIcon from '@mui/icons-material/CurrencyExchange';
import { ReactElement } from 'react';

export interface MenuItem {
  text: string
  icon: ReactElement
  path: string
}

export const companyMenuItems = {
    general: [
        { label: "Manage Projects", path: "/app/home-company", icon: <DashboardIcon /> },
        { label: "Available Tokens", path: "/app/available-tokens", icon: <MonetizationOnIcon /> },
    ],
    operations: [
        { label: "Withdrawal Requests", path: "/app/get-withdrawal-requests", icon: <AccountBalanceWalletIcon /> },
        { label: "Reserve Contributions", path: "/app/get-reserve-fund-contributions", icon: <CurrencyExchangeIcon /> },
        { label: "Contracts Balance Movements", path: "/app/get-contract-balance-movements", icon: <PaymentIcon /> },
    ]
};

export const inversorMenuItems = {
    general: [
        { label: "Available Projects", path: "/app/home-investor", icon: <DashboardIcon /> },
    ],
    operations: [
        { label: "Portfolio", path: "/app/user-portfolio", icon: <PortfolioIcon /> },
        { label: "Payments", path: "/app/user-payments", icon: <PaymentIcon /> },
    ]
};