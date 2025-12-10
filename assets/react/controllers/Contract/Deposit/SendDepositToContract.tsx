// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { useStellarWallet, Wallet } from "../../../hooks/StellarWalletsHook";
import { Fragment, useContext, useEffect, useState } from "react";
import { Box, Button, Card, CardContent, CircularProgress, Divider, Grid2, LinearProgress, TextField, Typography } from "@mui/material";
import { useNavigate, useParams } from "react-router-dom";
import { useApi } from "../../../hooks/ApiHook";
import axios, { AxiosError, AxiosResponse } from "axios";
import { Api } from "@stellar/stellar-sdk/lib/rpc";
import { ContractHook, SorobanStatus, useContract } from "../../../hooks/ContractHook";
import { ContractOutput } from "../../../model/contract";
import { getSorobanNetwork, BackendContext, BackendContextData } from "../../../context/BackendContext";
import { useQuery } from "@tanstack/react-query";
import ConnectWalletToSendDepositInfoModal from "./ConnectWalletToSendDepositInfoModal";
import CheckWalletTokenBalanceModal from "./CheckWalletTokenBalanceModal";
import SentDepositResultInfoModal from "./SentDepositResultInfoModal";
import BlockchainNetworkOutOfServiceInfoModal from "./BlockchainNetworkOutOfServiceInfoModal";
import { useApiRoutes } from "../../../hooks/ApiRoutesHook";
import { LoadingBox } from "../../Theme/Styled/Box";

interface FormData {
  amount: string,
}

export default function SendDepositToContract() {

  const { callGet, callPost } = useApi();
  const routes = useApiRoutes();

  const backendCtx: BackendContextData = useContext(BackendContext);
  const wallet: Wallet = useStellarWallet(getSorobanNetwork(backendCtx));
  const contractHook: ContractHook = useContract();
  const navigate = useNavigate();

  const [formData, setFormData] = useState<FormData>({ amount: '' });
  const [contract, setContract] = useState<ContractOutput>(null);
  const [contractTokenBalance, setContractTokenBalance] = useState<string>(null);
  const [buttonWalletRendered, setButtonWalletRendered] = useState<boolean>(false);
  const params = useParams();

  const [openModal, setOpenModal] = useState<boolean>(false);
  const [openModalHelp, setOpenModalHelp] = useState<boolean>(false);
  const [openModalBlockchainNotAvailableHelp, setOpenModalBlockchainNotAvailableHelp] = useState<boolean>(false);
  const [openSuccessDepositModal, setOpenSuccessDepositModal] = useState<boolean>(false);

  const [sendingDeposit, setSendingDeposit] = useState<boolean>(false);



  const handleChange = (event: any) => {
    const { name, value } = event.target;
    setFormData((previousFormData) => ({ ...previousFormData, [name]: value }))
  }

  const handleCloseModal = () => {
    setOpenModal(false);
  };

  const handleCloseModalHelp = () => {
    setOpenModalHelp(false);
  };

  const handleCloseModalBlockchainNotAvailableHelp = () => {
    setOpenModalBlockchainNotAvailableHelp(false);
  };

  const handleNavigateToPortfolio = () => {
    navigate('/app/user-portfolio');
  }

  const handlekeepHere = () => {
    setOpenSuccessDepositModal(false);
  }

  const handleOnBalanceRetrieved = (balance: string) => {
    setContractTokenBalance(balance);
  }

  const handleInvestment = async () => {
    const address = await wallet.getWallet().getAddress();
    contractHook.investOnProject(wallet.getWallet(), contract, formData.amount).then(
      (sendTransactionResponse: Api.SendTransactionResponse) => {
        setSendingDeposit(true);
        const createUserContractInvestmentDto = {
          contractAddress: contract.address,
          hash: sendTransactionResponse.hash,
          deposited: formData.amount,
          status: sendTransactionResponse.status,
          fromAddress: address.address
        };

        callPost(routes.createUserContractInvestment, createUserContractInvestmentDto).then(
          (uc: any) => {
            setSendingDeposit(false);
            setOpenSuccessDepositModal(true);
          }
        );
      }
    )
  }

  const query = useQuery(
    {
      queryKey: ['edit-contract', params.id],
      queryFn: async () => {
        const result: AxiosResponse<ContractOutput> | AxiosError = await callGet<object, ContractOutput>(routes.editContract(params.id), {});
        if (!axios.isAxiosError(result)) {
          setContract(result.data);
          contractHook.init(backendCtx.sorobanRpcUrl).then(
            (sorobanStatus: SorobanStatus) => {
              (sorobanStatus == SorobanStatus.HEALTHY)
                ? setOpenModalHelp(true)
                : setOpenModalBlockchainNotAvailableHelp(true)
            }
          );
          return result.data;
        }

        throw new Error(result.message);
      },
      refetchOnWindowFocus: false,
      retry: 0
    }
  );

  useEffect(() => {
    if (wallet.getWallet()) {
      setOpenModal(true);
    }
  }, [wallet.getWallet()])

  if (query.isLoading || !contract) {

    return (
      <LoadingBox>
        <CircularProgress sx={{ mb: 2 }} />
        <Typography variant="subtitle1" color="textSecondary">
          Loading contract data
        </Typography>
      </LoadingBox>
    )
  }

  if (sendingDeposit) {

    return (
      <LoadingBox>
        <CircularProgress sx={{ mb: 2 }} />
        <Typography variant="subtitle1" color="textSecondary">
          Processing deposit. This operation may take a few seconds. Please wait ...
        </Typography>
      </LoadingBox>
    );
  }

  return (
    <Fragment>
      <Box sx={{ flexGrow: 1, p: { xs: 2, sm: 4 } }}>
        <Grid2 container spacing={2}>
          <Grid2 size={12}>
            <Box sx={{ display: 'flex', flexDirection: { xs: 'column', sm: 'row' }, justifyContent: 'space-between', alignItems: 'center' }}>
              <Typography variant="h5" gutterBottom sx={{ fontSize: { xs: '1.2rem', sm: 'h4.fontSize' } }}> 
                Invest on project: {contract.label}
              </Typography>
              <Fragment>
                <div id="btnWalletConnect" ref={(node) => {
                  if (node && wallet && !buttonWalletRendered) {
                    wallet.generateButton(node.id);
                    setButtonWalletRendered(true);
                  }
                }}></div>
              </Fragment>
            </Box>
          </Grid2>
          <Grid2 size={{ xs: 12, sm: 6 }}>
            <Card
              sx={{
                height: '100%',
                display: 'flex',
                flexDirection: 'column',
                boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
                borderRadius: 2,
              }}
            >
              <CardContent sx={{ flexGrow: 1, p: 4 }}>
                <Typography variant="h6" gutterBottom>
                  Investment
                </Typography>
                <TextField
                  fullWidth
                  name="amount"
                  label="Amount to invest"
                  variant="outlined"
                  required
                  size="small"
                  placeholder="Enter the amount you want to invest"
                  value={formData.amount}
                  onChange={handleChange}
                  sx={{ marginBottom: 2 }} // Espacio inferior
                />
                <Button
                  variant="contained"
                  color="primary"
                  onClick={handleInvestment}
                  fullWidth
                  sx={{ py: 1.5, fontWeight: 'bold' }} // Botón más grande y destacado
                  disabled={!contractTokenBalance || (parseFloat(contractTokenBalance) < contract.minPerInvestment) || !wallet.getWallet()}
                >
                  Invest
                </Button>

                <Divider sx={{ my: 2 }} />
                <Typography variant="caption" color="textSecondary" align="center" sx={{ fontStyle: 'italic', padding: 1, border: '1px solid #ddd', borderRadius: 1 }}>
                  Note: All investments involve risks, including the possible loss of your investment.
                </Typography>
              </CardContent>
            </Card>
          </Grid2>
          <Grid2 size={{ xs: 12, sm: 6 }}>
            <Card
              sx={{
                height: '100%',
                display: 'flex',
                flexDirection: 'column',
                boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
                borderRadius: 2,
                backgroundColor: '#f9f9f9',
              }}
            >
              <CardContent sx={{ flexGrow: 1, p: 4 }}>
                <Typography variant="h6" gutterBottom>
                  Project details
                </Typography>
                <Divider sx={{ my: 2 }} />
                <Grid2 container spacing={2}>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Fundraising goal
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.goal}
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Issuer company
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.issuer}
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Rate
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.rate}%
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Minimum deposit
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.minPerInvestment.toLocaleString('en-US', { style: 'currency', currency: 'USD' })}
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Months to wait before receiving capital gains
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.claimMonths}
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Deposit token
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.tokenContract.name} ({contract.tokenContract.code})
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Started At
                    </Typography>
                    <Typography variant="body2" fontWeight="medium">
                      {contract.initializedAt}
                    </Typography>
                  </Grid2>
                  <Grid2 size={{ xs: 12, sm: 6 }}>
                    <Typography variant="subtitle2" color="textSecondary">
                      Funds raised
                    </Typography>
                    <Box sx={{ display: 'flex', alignItems: 'center', mt: 1 }}>
                      <LinearProgress
                        variant="determinate"
                        value={contract.percentageFundsReached}
                        sx={{ flexGrow: 1, mr: 1, height: 8, borderRadius: 1 }} // Barra más alta y con bordes redondeados
                      />
                      <Typography variant="body2" fontWeight="medium">
                        {contract.contractBalance.percentajeFundsReceived}%
                      </Typography>
                    </Box>
                    <Typography variant="caption" color="textSecondary">
                      {contract.contractBalance.available} / {contract.goal}
                    </Typography>
                  </Grid2>
                </Grid2>
                <Divider sx={{ my: 2 }} />
                <Typography variant="body2" align="justify">
                  {contract.description}
                </Typography>
              </CardContent>
            </Card>
          </Grid2>
        </Grid2>
      </Box>

      <ConnectWalletToSendDepositInfoModal openModalHelp={openModalHelp} handleCloseModalHelp={handleCloseModalHelp} ></ConnectWalletToSendDepositInfoModal>
      <BlockchainNetworkOutOfServiceInfoModal openModalHelp={openModalBlockchainNotAvailableHelp} handleCloseModalHelp={handleCloseModalBlockchainNotAvailableHelp} ></BlockchainNetworkOutOfServiceInfoModal>
      <CheckWalletTokenBalanceModal openModal={openModal} handleCloseModal={handleCloseModal} handleOnBalanceRetrieved={handleOnBalanceRetrieved} wallet={wallet.getWallet()} contract={contract} ></CheckWalletTokenBalanceModal>
      <SentDepositResultInfoModal openModal={openSuccessDepositModal} handleClose={handlekeepHere} handleNavigateToPortfolio={handleNavigateToPortfolio} ></SentDepositResultInfoModal>

    </Fragment>
  )
}