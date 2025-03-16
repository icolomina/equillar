import { WalletNetwork } from "@creit.tech/stellar-wallets-kit";
import { useStellarWallet } from "../../hooks/Wallet/StellarWalletsHook";
import { Fragment, useEffect, useState } from "react";
import { Backdrop, Box, Button, Card, CardContent, CircularProgress, Divider, Grid2, LinearProgress, Tab, Tabs, TextField, Typography } from "@mui/material";
import { useNavigate, useParams } from "react-router-dom";
import { useApi } from "../../hooks/ApiHook";
import { ContractInvestment } from "../HomeCompany";
import { AxiosResponse } from "axios";
import { CreateUserContractInvestmentPath, EditContractPath } from "../../services/Api/Investment/ApiRoutes";
import { sprintf } from 'sprintf-js';
import { ScContract } from "../../services/api/contract";
import { isConnected } from "@stellar/freighter-api";
import Modal from '@mui/material/Modal';
import { Api } from "@stellar/stellar-sdk/lib/rpc";
import { CreateUserContractInvestmentDto } from "../../dto/dto";

interface FormData {
    amount: string,
}

export default function SendInvestmentDeposit() {

    const {callGet, callPost} = useApi();
    const navigate = useNavigate();

    const [formData, setFormData] = useState<FormData>({amount: ''});
    const [contract, setContract] = useState<ContractInvestment>(null);
    const [backdropLoading, setBakdropLoading] = useState<boolean>(false);
    const params = useParams();

    const [isWalletConnected, setIsWalletConnected] = useState(false);
    const [isWalletModalOpen, setIsWalletModalOpen] = useState(false);

    const checkWalletConnection = async () => {
        try {
          const isConn = await isConnected();
          setIsWalletConnected(isConn);
          if (!isConn) {
            setIsWalletModalOpen(true);
          }

        } catch (error) {
          console.error("Error checking wallet connection:", error);
          setIsWalletConnected(false);
          setIsWalletModalOpen(true);
        }
      };

    useEffect(() => {
        checkWalletConnection();
    }, []);

    const handleChange = (event: any) => {
        const { name, value } = event.target;
        setFormData ( (previousFormData) => ({ ...previousFormData, [name]: value}) )
    }

    const handleInvestment = async() => {
        const scContract = new ScContract();
        scContract.init('https://soroban-testnet.stellar.org');
        scContract.sendDeposit(contract.address, formData.amount).then(
          async (sendTransactionResponse: Api.SendTransactionResponse) => {
            const createUserContractInvestmentDto: CreateUserContractInvestmentDto = {
              contractAddress: contract.address,
              hash: sendTransactionResponse.hash,
              deposited: formData.amount,
              status: sendTransactionResponse.status
            };

            const result = await callPost(CreateUserContractInvestmentPath, createUserContractInvestmentDto);
            console.log(result);
            navigate('/app/user-investments');
          }
        )
    }

    useEffect(() => {
        setBakdropLoading(true);
        callGet<object, ContractInvestment>(sprintf(EditContractPath, params.id), {}).then(
        (response: AxiosResponse) => {
            setContract(response.data);
            console.log(response.data);
            setBakdropLoading(false);
        })
    }, []);

    if (backdropLoading || !contract) {
        return (
          <Fragment>
            <Backdrop
              sx={(theme) => ({ color: "#fff", zIndex: theme.zIndex.drawer + 1 })}
              open={backdropLoading}
              //onClick={handleClose}
            >
              <CircularProgress color="inherit" />
            </Backdrop>
          </Fragment>
        );
      }

    /*return (
        <Fragment>
        <Box sx={{ flexGrow: 1, p: 2 }}>
            <Grid2 container spacing={2}>
                <Grid2
                    size={12}
                    sx={{
                    display: "flex",
                    justifyContent: "space-between",
                    alignItems: "center",
                    }}
                >
                    <Typography variant="h4">
                    Invertir en el proyecto {contract.label}
                    </Typography>
                </Grid2>
                <Grid2 size={12}>
                    <Divider sx={{ marginY: 2 }} />
                </Grid2>
                <Grid2 container size={{ xs: 12, md: 6 }} spacing={3}>
                    <TextField
                    fullWidth
                    name="amount"
                    label="Amount to invest"
                    variant="outlined"
                    required
                    size="small"
                    placeholder="Introduce the amount you want to invest"
                    value={formData.amount}
                    onChange={handleChange}
                    />

                </Grid2>
                <Grid2 size={12}>
                    <Divider sx={{ marginY: 2 }} />
                </Grid2>
                <Grid2 container sx={{ display: 'flex', justifyContent: 'flex-start', marginTop: 2 }}>
                    <Button variant="contained" color="primary" onClick={handleInvestment}>
                        Invest
                    </Button>
                </Grid2>
            </Grid2>
        </Box>
        {isWalletModalOpen && (
            <Modal
              open={isWalletModalOpen}
              onClose={() => setIsWalletModalOpen(false)}
              aria-labelledby="wallet-modal-title"
              aria-describedby="wallet-modal-description"
            >
              <Box
                sx={{
                  position: "absolute",
                  top: "50%",
                  left: "50%",
                  transform: "translate(-50%, -50%)",
                  width: 400,
                  bgcolor: "background.paper",
                  border: "2px solid #000",
                  boxShadow: 24,
                  p: 4,
                }}
              >
                <Typography id="wallet-modal-title" variant="h6" component="h2">
                  Wallet not connected
                </Typography>
                <Typography id="wallet-modal-description" sx={{ mt: 2 }}>
                  Please connect your wallet to continue.
                </Typography>
              </Box>
            </Modal>
          )}
        </Fragment>
    );*/

    return (
      <Fragment>
  <Box sx={{ flexGrow: 1, p: 4 }}>
    <Grid2 container spacing={4}>
      <Grid2 size={12}>
        <Typography variant="h4" gutterBottom>
          Invertir en el proyecto {contract.label}
        </Typography>
      </Grid2>
      <Grid2 size={6}>
        <Card
          sx={{
            height: '100%',
            display: 'flex',
            flexDirection: 'column',
            boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)', // Sombra más sutil
            borderRadius: 2, // Bordes ligeramente redondeados
          }}
        >
          <CardContent sx={{ flexGrow: 1, p: 4 }}>
            <Typography variant="h6" gutterBottom>
              Inversión
            </Typography>
            <TextField
              fullWidth
              name="amount"
              label="Cantidad a invertir"
              variant="outlined"
              required
              size="small"
              placeholder="Introduce la cantidad"
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
            >
              Invertir
            </Button>
            <Divider sx={{ my: 2 }} />
            <Typography variant="caption" color="textSecondary" align="center" sx={{ fontStyle: 'italic', padding: 1, border: '1px solid #ddd', borderRadius: 1 }}>
              Nota: Toda inversión conlleva riesgos, incluyendo la posible pérdida del capital invertido.
            </Typography>
          </CardContent>
        </Card>
      </Grid2>
      <Grid2 size={6}>
        <Card
          sx={{
            height: '100%',
            display: 'flex',
            flexDirection: 'column',
            boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
            borderRadius: 2,
            backgroundColor: '#f9f9f9', // Ligero cambio de color de fondo
          }}
        >
          <CardContent sx={{ flexGrow: 1, p: 4 }}>
            <Typography variant="h6" gutterBottom>
              Detalles del Proyecto
            </Typography>
            <Divider sx={{ my: 2 }} />
            <Grid2 container spacing={2}>
              {/* Reorganización de la información */}
              <Grid2 size={6}>
                <Typography variant="subtitle2" color="textSecondary">
                  Objetivo de fondos
                </Typography>
                <Typography variant="body2" fontWeight="medium">
                  {contract.goal}
                </Typography>
              </Grid2>
              <Grid2 size={6}>
                <Typography variant="subtitle2" color="textSecondary">
                  Empresa emisora
                </Typography>
                <Typography variant="body2" fontWeight="medium">
                  {contract.issuer}
                </Typography>
              </Grid2>
              <Grid2 size={6}>
                <Typography variant="subtitle2" color="textSecondary">
                  Tasa de retorno
                </Typography>
                <Typography variant="body2" fontWeight="medium">
                  {contract.rate}%
                </Typography>
              </Grid2>
              <Grid2 size={6}>
                <Typography variant="subtitle2" color="textSecondary">
                  Meses para reclamar ganancias
                </Typography>
                <Typography variant="body2" fontWeight="medium">
                  {contract.claimMonths}
                </Typography>
              </Grid2>
              <Grid2 size={6}>
                <Typography variant="subtitle2" color="textSecondary">
                  Token de depósitos
                </Typography>
                <Typography variant="body2" fontWeight="medium">
                  {contract.token} ({contract.tokenCode})
                </Typography>
              </Grid2>
              <Grid2 size={6}>
                <Typography variant="subtitle2" color="textSecondary">
                  Iniciado en
                </Typography>
                <Typography variant="body2" fontWeight="medium">
                  {contract.initializedAt}
                </Typography>
              </Grid2>
              <Grid2 size={12}>
                <Typography variant="subtitle2" color="textSecondary">
                  Fondos recaudados
                </Typography>
                <Box sx={{ display: 'flex', alignItems: 'center', mt: 1 }}>
                  <LinearProgress
                    variant="determinate"
                    value={
                      ((contract.contractBalance.available + contract.contractBalance.reserveFund) /
                        contract.goal) *
                      100
                    }
                    sx={{ flexGrow: 1, mr: 1, height: 8, borderRadius: 1 }} // Barra más alta y con bordes redondeados
                  />
                  <Typography variant="body2" fontWeight="medium">
                    {((contract.contractBalance.available + contract.contractBalance.reserveFund) /
                      contract.goal) *
                      100}
                    %
                  </Typography>
                </Box>
                <Typography variant="caption" color="textSecondary">
                  {contract.contractBalance.available + contract.contractBalance.reserveFund} / {contract.goal}
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
  {/* Modal de wallet (sin cambios) */}
  {isWalletModalOpen && (
    <Modal
      open={isWalletModalOpen}
      onClose={() => setIsWalletModalOpen(false)}
      aria-labelledby="wallet-modal-title"
      aria-describedby="wallet-modal-description"
    >
      <Box
        sx={{
          position: 'absolute',
          top: '50%',
          left: '50%',
          transform: 'translate(-50%, -50%)',
          width: 400,
          bgcolor: 'background.paper',
          border: '2px solid #000',
          boxShadow: 24,
          p: 4,
        }}
      >
        <Typography id="wallet-modal-title" variant="h6" component="h2">
          Wallet no conectado
        </Typography>
        <Typography id="wallet-modal-description" sx={{ mt: 2 }}>
          Por favor, conecta tu wallet para continuar.
        </Typography>
      </Box>
    </Modal>
  )}
</Fragment>
    )
}