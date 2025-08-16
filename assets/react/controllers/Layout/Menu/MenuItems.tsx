import AccountTreeIcon from '@mui/icons-material/AccountTree';
import TokenIcon from '@mui/icons-material/Token';
import MoneyOffIcon from '@mui/icons-material/MoneyOff';
import BusinessIcon from '@mui/icons-material/Business';
import PieChartIcon from '@mui/icons-material/PieChart';
import SavingsIcon from '@mui/icons-material/Savings'; 
import { ReactElement } from 'react';

export interface MenuItem {
  text: string
  icon: ReactElement
  path: string
}

export const companyMenuItems: MenuItem[] = [
  {
    text: 'Projects',
    icon: <AccountTreeIcon />,
    path: '/app/home-company',
  },
  {
    text: 'Avalable tokens',
    icon: <TokenIcon />,
    path: '/app/available-tokens',
  },
  {
    text: 'Withdrawal Requests',
    icon: <MoneyOffIcon />, 
    path: '/app/get-withdrawal-requests',
  },
  {
    text: 'Reserve Contributions',
    icon: <SavingsIcon />, 
    path: '/app/get-reserve-fund-contributions',
  }
];

export const inversorMenuItems: MenuItem[] = [
  {
    text: 'Available Projects',
    icon: <BusinessIcon />,
    path: '/app/home-investor',
  },
  {
    text: 'Portfolio',
    icon: <PieChartIcon />,
    path: '/app/user-portfolio',
  },
  {
    text: 'Payments',
    icon: <MoneyOffIcon />,
    path: '/app/user-payments',
  }
];