import {
  BrowserRouter as Router,
  Routes,
  Route
} from "react-router-dom";
import Layout from "./Layout/Layout";
import Home from "./Home/Home";
import SignIn from "./Login/SignIn";
import { CssBaseline, ThemeProvider } from "@mui/material";
import { Fragment } from "react/jsx-runtime";

import "@fontsource/roboto/300.css";
import "@fontsource/roboto/400.css";
import "@fontsource/roboto/500.css";
import "@fontsource/roboto/700.css";
import ProtectedRoute from "./ProtectedRoute";
import HomeInvestor from "./Home/HomeInvestor";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import theme from "./Theme/Theme";
import HomeCompany from "./Home/HomeCompany";
import TokenList from "./Token/TokenList";
import UserPortfolio from "./Portfolio/UserPortfolio";
import UserContractPayments from "./Contract/User/UserContractPayments";
import GetWithdrawalRequests from "./Company/Withdrawal/GetWithdrawalRequests";
import SendDepositToContract from "./Contract/Deposit/SendDepositToContract";
import StartContract from "./Contract/StartContract";
import CreateContract from "./Contract/Form/ContractForm";
import WithdrawalRequestConfirmed from "./Company/Withdrawal/WithdrawalRequestConfirmed";
import ViewContract from "./Contract/ViewContract";
import EditContract from "./Contract/EditContract";
import GetReserveFundsContributions from "./Company/ReserveFund/GetReserveFundsContributions";
import EditUserContract from "./Contract/User/EditUserContract";
import { BackendContext, BackendContextData } from "../context/BackendContext";

interface AppProps {
  sorobanNetworkPassphrase?: string;
  sorobanRpcUrl?: string
  webserverEndpoint?: string
}

export default function App(props: AppProps) {
  const queryClient = new QueryClient();
  const SorobanContextProvider = BackendContext.Provider;
  const sorobanContextData: BackendContextData =  { 
    sorobanNetworkPassphrase: props.sorobanNetworkPassphrase,
    sorobanRpcUrl: props.sorobanRpcUrl,
    webserverEndpoint: props.webserverEndpoint
  }

  return (
    <Fragment>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        <QueryClientProvider client={queryClient}>
          <SorobanContextProvider value={sorobanContextData} >
            <Router>
              <Routes>
                <Route path="/login" element={<SignIn />} />
                <Route path="/withdrawal-confirmed" element={<WithdrawalRequestConfirmed />} />
                <Route path="/app" element={<ProtectedRoute children={<Layout />} />}>
                  <Route index element={<Home />} />
                  <Route path="home-investor" element={<HomeInvestor />} />
                  <Route path="home-company" element={<HomeCompany />} />
                  <Route path="user-portfolio" element={<UserPortfolio />} />
                  <Route path="create-project" element={<CreateContract />} />
                  <Route path="project/:id/start" element={<StartContract />} />
                  <Route path="project/:id/invest" element={<SendDepositToContract />} />
                  <Route path="project/:id/view" element={<ViewContract />} />
                  <Route path="project/:id/edit" element={<EditContract />} />
                  <Route path="user-contract/:id/edit" element={<EditUserContract />} />
                  <Route path="available-tokens" element={<TokenList />} />
                  <Route path="get-withdrawal-requests" element={<GetWithdrawalRequests />} />
                  <Route path="user-payments" element={<UserContractPayments />} />
                  <Route path="get-reserve-fund-contributions" element={<GetReserveFundsContributions />} />
                </Route>
              </Routes>
            </Router>
          </SorobanContextProvider>
        </QueryClientProvider>
      </ThemeProvider>
    </Fragment>
  );
}
