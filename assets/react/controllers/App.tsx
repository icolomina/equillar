import {
  BrowserRouter as Router,
  Routes,
  Route,
  useNavigate,
} from "react-router-dom";
import Layout from "./Layout";
import Home from "./Home";
import Blogs from "./Blogs";
import SignIn from "./Login/SignIn";
import CreateInvestmentProject from "./Investment/Project/CreateInvestmentProject";
import { createTheme, CssBaseline, ThemeProvider } from "@mui/material";
import { Fragment } from "react/jsx-runtime";

import "@fontsource/roboto/300.css";
import "@fontsource/roboto/400.css";
import "@fontsource/roboto/500.css";
import "@fontsource/roboto/700.css";
import StartInvestmentProject from "./Investment/Project/StartInvestmentProject";
import ProtectedRoute from "./ProtectedRoute";
import HomeInvestor from "./HomeInvestor";
import SendInvestmentDeposit from "./Investment/SendInvestmentDeposit";
import { WalletContext } from "../context/WalletContext";
import { useStellarWallet, Wallet } from "../hooks/Wallet/StellarWalletsHook";
import { WalletNetwork } from "@creit.tech/stellar-wallets-kit";
import { createContext } from "react";
import { ReloadedRoute, useReloadedRoute } from "../hooks/ReloadedRouteContext";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import theme from "./Theme/Theme";
import { useAuth } from "../hooks/AuthHook";

export const ReloadRouteContext = createContext<ReloadedRoute>(null);

interface AppProps {
  pathSlug?: string;
}

export default function App(props: AppProps) {
  console.log("llega");
  const wallet: Wallet = useStellarWallet(WalletNetwork.TESTNET);
  const queryClient = new QueryClient();
  const {isCompany} = useAuth();

  return (
    <Fragment>
      <ThemeProvider theme={theme}>
        <CssBaseline />
          <WalletContext.Provider value={wallet}>
            <QueryClientProvider client={queryClient}>
              <Router>
                <Routes>
                  <Route path="/login" element={<SignIn />} />
                  <Route path="/app" element={<ProtectedRoute children={<Layout />} />}>
                    <Route index element={<Home />} />
                    <Route path="home-investor" element={<HomeInvestor />} />
                    <Route path="blogs" element={<Blogs />} />
                    <Route path="create-project" element={<CreateInvestmentProject />} />
                    <Route path="project/:id/start" element={<StartInvestmentProject />} />
                    <Route path="project/:id/invest" element={<SendInvestmentDeposit />} />
                  </Route>
                </Routes>
              </Router>
            </QueryClientProvider>
          </WalletContext.Provider>
      </ThemeProvider>
    </Fragment>
  );
}
