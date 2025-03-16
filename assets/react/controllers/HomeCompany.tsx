import { Fragment, useEffect, useState } from "react";
import { useApi } from "../hooks/ApiHook";
import { ApproveContractPath, GetContractsPath } from "../services/Api/Investment/ApiRoutes";
import { AxiosResponse } from "axios";
import { Backdrop, Box, Button, CircularProgress, Divider, Grid2, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography, useMediaQuery, useTheme } from "@mui/material";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../hooks/AuthHook";
import { sprintf } from 'sprintf-js';

export interface ContractInvestmentBalance {
  available: number,
  reserveFund: number,
  commision?: number
}

export interface ContractInvestment {
  id: number
  initialized: boolean,
  address: string,
  token: string,
  tokenDecimals: number,
  tokenCode: string,
  rate: number,
  createdAt: string,
  initializedAt: string,
  issuer: string,
  claimMonths: number,
  label: string
  fundsReached: boolean,
  description?: string
  shortDescription?: string,
  currentFunds?: string,
  contractBalance?: ContractInvestmentBalance,
  status: string,
  goal: number
}

export default function HomeCompany() {

    const { callGet, callPatch } = useApi();
    //const [loading, setLoading] = useState<boolean>(false);
    const [contracts, setContracts] = useState<ContractInvestment[]>([]);
    const [backdropLoading, setBakdropLoading] = useState<boolean>(false);
    const navigate = useNavigate();
    const {isAdmin} = useAuth();

    const theme = useTheme();
    const fullScreen = useMediaQuery(theme.breakpoints.down('md'));

    useEffect(() => {
      handleGetProjects();
    }, []);

    const handleCreateProject = () => {
      return navigate('/app/create-project')
    }

    const handleGetProjects = () => {
      setBakdropLoading(true);
      callGet<object, ContractInvestment[]>(GetContractsPath, {}).then(
        (response: AxiosResponse) => {
          console.log(response.data);
          setContracts(response.data);
          setBakdropLoading(false);
        }
      )
    }

    const handleEdit = (c: ContractInvestment) => {
    }
    
    const handleStart = (c: ContractInvestment) => {
      return navigate('/app/project/' + c.id + '/start');
    }

    const handleApprove = (c: ContractInvestment) => {
      setBakdropLoading(true);
      callPatch(sprintf(ApproveContractPath, c.id), {}).then(
        () => {
          handleGetProjects();
        }
      )
    }

    const getStatusColor = (status) => {
      switch (status) {
        case 'APPROVED':
        case 'ACTIVE':
          return 'green';
        case 'REJECTED':
          return 'red';
        case 'REVIEWING':
          return 'orange';
        default:
          return 'black'; // Color por defecto
      }
    };

    const style = {
      position: 'absolute',
      top: '50%',
      left: '50%',
      transform: 'translate(-50%, -50%)',
      width: 400,
      bgcolor: 'background.paper',
      border: '2px solid #000',
      boxShadow: 24,
      p: 4,
    };

    if(backdropLoading) {
      return (
        <Fragment>
          <Backdrop
              sx={(theme) => ({ color: '#fff', zIndex: theme.zIndex.drawer + 1 })}
              open={backdropLoading}
              //onClick={handleClose}
              >
            <CircularProgress color="inherit" />
            </Backdrop>
        </Fragment>
      );
    }
    else {
      if(contracts.length === 0) {
        return (
          <Fragment>
            <Box sx={{ p: 2, mt: 2, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', height: '100%' }} >
              <Typography variant="h6" sx={{ mb: 2 }}>
                Aun no has dado de alta ningun proyecto
              </Typography>
              <Button variant="contained" color="primary" onClick={() => handleCreateProject() }>
                Dar de alta un nuevo proyecto
              </Button>
            </Box>

          </Fragment>
        );
      }
    }

    return (
        <Fragment>
          <Box sx={{ flexGrow: 1, p: 2  }} >
            <Grid2 container spacing={2} >
            <Grid2
                size={12}
                sx={{
                    display: 'flex',
                    justifyContent: 'flex-end', // Alinea el contenido a la derecha
                    alignItems: 'center', // Centra verticalmente el contenido
                }}
            >
                <Typography variant="h4" sx={{ marginRight: 'auto' }}>
                    Listado de proyectos
                </Typography>
                <Button variant="contained" color="primary" onClick={handleCreateProject}>
                    Crear nuevo proyecto
                </Button>
            </Grid2>
              <Grid2 size={12}>
                    <Divider sx={{ marginY: 2 }} />
              </Grid2>
              <Grid2 container size={12}>
                <TableContainer component={Paper}>
                  <Table sx={{ minWidth: 650 }} aria-label="simple table">
                    <TableHead>
                      <TableRow>
                        <TableCell>Project name</TableCell>
                        <TableCell align="right">Token</TableCell>
                        <TableCell align="right">Rate</TableCell>
                        <TableCell align="right">Claim Months</TableCell>
                        <TableCell align="right">Goal</TableCell>
                        <TableCell align="right">Status</TableCell>
                        <TableCell align="right">Options</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {contracts.map((c: ContractInvestment) => (
                        <TableRow
                          key={c.label}
                          sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                        >
                          <TableCell component="th" scope="row">
                            {c.label}
                          </TableCell>
                          <TableCell align="right">{c.token} ({c.tokenCode})</TableCell>
                          <TableCell align="right">{c.rate}</TableCell>
                          <TableCell align="right">{c.claimMonths}</TableCell>
                          <TableCell align="right">${ Intl.NumberFormat('es-ES').format(c.goal) }</TableCell>
                          <TableCell align="right">
                            <Typography sx={{ color: getStatusColor(c.status) }}>
                              {c.status}
                            </Typography>
                          </TableCell>
                          <TableCell align="right">
                            <Button variant="contained" color="primary" size="small" sx={{ mr: 1 }}  onClick={() => handleEdit(c)}>
                              Edit
                            </Button>
                            { (isAdmin() && c.status === 'REVIEWING') &&
                              <Button variant="contained" color="success" size="small" sx={{ mr: 1 }}  onClick={() => handleApprove(c)}>
                                Approve
                              </Button>
                            }
                            { c.status === 'APPROVED' && 
                              <Button variant="contained" color="success" size="small" onClick={() => handleStart(c)}>
                                Start
                              </Button>
                            }
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </TableContainer>
            </Grid2>
          </Grid2>
          </Box>
        </Fragment>
      );
}

