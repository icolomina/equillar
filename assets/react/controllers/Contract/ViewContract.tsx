// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Fragment, useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { useNavigate, useParams } from "react-router-dom";
import axios, { AxiosError, AxiosResponse } from "axios";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import { sprintf } from 'sprintf-js';
import { Backdrop, Badge, Box, Button, Card, CardContent, CircularProgress, Dialog, DialogContent, DialogTitle, Divider, Grid2, IconButton, LinearProgress, ListSubheader, Menu, MenuItem, Typography } from "@mui/material";
import { ContractOutput } from "../../model/contract";
import CloseIcon from '@mui/icons-material/Close';
import PdfViewer from "../Miscelanea/PdfViewer";
import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import PauseCircleIcon from '@mui/icons-material/PauseCircle';
import PendingIcon from '@mui/icons-material/Pending';
import VerifiedIcon from '@mui/icons-material/Verified';
import { formatCurrencyFromValueAndTokenContract } from "../../utils/currency";
import PlayCircleFilledIcon from '@mui/icons-material/PlayCircleFilled';
import PaymentsIcon from '@mui/icons-material/Payments';
import AccountBalanceWalletIcon from '@mui/icons-material/AccountBalanceWallet';
import PriceChangeIcon from '@mui/icons-material/PriceChange';
import EditIcon from '@mui/icons-material/Edit';
import CancelIcon from '@mui/icons-material/Cancel';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import { useAuth } from "../../hooks/AuthHook";
import ApproveContractModal from "./ApproveContractModal";
import ActivateContractModal from "./ActivateContractModal";
import CreateWithdrawalRequestModal from "../Company/Withdrawal/CreateWithdrawalRequestModal";
import CreateReserveFundContributionModal from "../Company/ReserveFund/CreateReserveFundContributionModal";
import CreateAvailableToReserveMovementModal from "../Company/Balance/CreateAvailableToReserveMovementModal";
import PauseContractInvestmentsModal from "./PauseContractInvestmentsModal";
import ResumeContractInvestmentsModal from "./ResumeContractInvestmentsModal";

export default function ViewContract() {

    const params = useParams();
    const { callGet, callGetDownloadFile } = useApi();
    const {isAdmin, isCompany} = useAuth();
    const navigate = useNavigate();
    const apiRoutes = useApiRoutes();

    const [openApproveModal, setOpenApproveModal] = useState(false);
    const [openActivationModal, setOpenActivationModal] = useState(false);
    const [openModalToRequestWithdrawal, setOpenModalToRequestWithdrawal] = useState<boolean>(false);
    const [openModalToRequestReserveFundContribution, setOpenModalToRequestReserveFundContribution] = useState<boolean>(false);
    const [openModalToRequestAvailableToReserveFundMovement, setOpenModalToRequestAvailableToReserveFundMovement] = useState<boolean>(false);
    const [openModalToPauseContract, setOpenModalToPauseContract] = useState<boolean>(false);
    const [openModalToResumeContract, setOpenModalToResumeContract] = useState<boolean>(false);
    const [contractSelected, setContractSelected] = useState<ContractOutput | null>(null);
    const [anchorElActions, setAnchorElActions] = useState<null | HTMLElement>(null);
    const openActionsMenu = Boolean(anchorElActions);

    const [contract, setContract] = useState<ContractOutput>(null);
    const [pdfUrl, setPdfUrl] = useState<string>(null);
    const [openPdfModal, setOpenPdfModal] = useState<boolean>(false);
    const [downloadingPdf, setDownloadingPdf] = useState<boolean>(false);

    const query = useQuery<ContractOutput>(
        {
            queryKey: ['edit-contract', params.id],
            queryFn: async () => {
                const result: AxiosResponse<ContractOutput> | AxiosError = await callGet<object, ContractOutput>(apiRoutes.editContract(params.id), {});
                if (!axios.isAxiosError(result)) {
                    return result.data;
                }

                throw new Error(result.message);
            },
            retry: 0
        }
    );

    const handleReadProjectDocument = () => {
        setDownloadingPdf(true);
        callGetDownloadFile(apiRoutes.getContractDocument(query.data.id), { 'Accept': 'application/pdf' }).then(
            (result: AxiosResponse | AxiosError) => {
                if (!axios.isAxiosError(result)) {
                    const pdfBlob = result.data;
                    setPdfUrl(URL.createObjectURL(pdfBlob));
                    setOpenPdfModal(true);
                    setDownloadingPdf(false);
                }
            }
        )
    }

    const handleClosePdfModal = () => {
        setOpenPdfModal(false);
        if (pdfUrl) {
            URL.revokeObjectURL(pdfUrl);
            setPdfUrl(null);
        }
    };

    const handleEditContract = () => {
        return navigate('/app/project/' + query.data.id + '/edit');
    }

    const handleCloseApproveModal= () => {
        setOpenApproveModal(false);
        setContractSelected(null);
    };

    const handleCloseActivationModal = () => {
        setOpenActivationModal(false);
        setContractSelected(null);
    }

    const handleCloseRequestWithdrawalModal = () => {
        setContractSelected(null);
        setOpenModalToRequestWithdrawal(false);
    }

    const handleCloseReserveFundContributionModal = () => {
        setContractSelected(null);
        setOpenModalToRequestReserveFundContribution(false);
    }

    const handleCloseAvailableToReserveFundMovementModal = () => {
        setContractSelected(null);
        setOpenModalToRequestAvailableToReserveFundMovement(false);
    }

    const handleClosePauseContractModal = () => {
        setContractSelected(null);
        setOpenModalToPauseContract(false);
    }

    const handleCloseResumeContractModal = () => {
        setContractSelected(null);
        setOpenModalToResumeContract(false);
    }

    const handleOpenModalForApprove = () => {
        setOpenApproveModal(true);
        setContractSelected(query.data);
    }

    const handleOpenModalForActivation = () => {
        setOpenActivationModal(true);
        setContractSelected(query.data);
    }

    const handleOpenModalForRequestWithdrawal = () => {
        setContractSelected(query.data);
        setOpenModalToRequestWithdrawal(true);
    }

    const handleOpenModalForReserveFundContribution = () => {
        setContractSelected(query.data);
        setOpenModalToRequestReserveFundContribution(true);
    }

    const handleOpenModalForAvailableToReserveFundMovement = () => {
        setContractSelected(query.data);
        setOpenModalToRequestAvailableToReserveFundMovement(true);
    }

    const handleOpenModalToPauseContract = () => {
        setContractSelected(query.data);
        setOpenModalToPauseContract(true);
    }

    const handleOpenModalToResumeContract = () => {
        setContractSelected(query.data);
        setOpenModalToResumeContract(true);
    }

    const handleApprovalFinished = async () => {
        await query.refetch();
    }

    const handleActivationFinished = async () => {
        await query.refetch();
    }

    const handlePauseContractFinished = async () => {
        await query.refetch();
    }

    const handleResumeContractFinished = async () => {
        await query.refetch();
    }

    const handleOpenActionsMenu = (event: React.MouseEvent<HTMLElement>) => {
        setAnchorElActions(event.currentTarget);
    };

    const handleCloseActionsMenu = () => {
        setAnchorElActions(null);
    };

    const getAvailableActions = () => {
        const actions = [];

        if (query.data.status !== 'ACTIVE') {
            actions.push({
                id: 'edit',
                label: 'Edit',
                icon: <EditIcon fontSize="small" />,
                onClick: () => { handleCloseActionsMenu(); handleEditContract(); }
            });
        }

        if (isAdmin() && query.data.status === 'REVIEWING') {
            actions.push({
                id: 'approve',
                label: 'Approve',
                icon: <PlayCircleFilledIcon fontSize="small" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalForApprove(); }
            });
        }

        if (query.data.status === 'APPROVED') {
            actions.push({
                id: 'activate',
                label: 'Activate',
                icon: <PlayCircleFilledIcon fontSize="small" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalForActivation(); }
            });
        }

        if (query.data.status === 'ACTIVE') {
            actions.push({
                id: 'pause',
                label: 'Pause Contract',
                icon: <CancelIcon fontSize="small" color="warning" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalToPauseContract(); }
            });
        }

        if (query.data.status === 'PAUSED') {
            actions.push({
                id: 'resume',
                label: 'Resume Contract',
                icon: <CancelIcon fontSize="small" color="success" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalToResumeContract(); }
            });
        }

        if (isCompany() && query.data.status === 'ACTIVE' && query.data.contractBalance.available > 0) {
            actions.push({
                id: 'withdrawal',
                label: 'Request funds withdrawal',
                icon: <PriceChangeIcon fontSize="small" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalForRequestWithdrawal(); }
            });
        }

        if ((isCompany() || isAdmin()) && query.data.status === 'ACTIVE') {
            actions.push({
                id: 'reserve_contribution',
                label: 'Reserve fund contribution',
                icon: <AccountBalanceWalletIcon fontSize="small" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalForReserveFundContribution(); }
            });
        }

        if ((isCompany() || isAdmin()) && query.data.status === 'ACTIVE' && query.data.contractBalance.available > 0) {
            actions.push({
                id: 'move_funds',
                label: 'Move funds to the reserve',
                icon: <PaymentsIcon fontSize="small" />,
                onClick: () => { handleCloseActionsMenu(); handleOpenModalForAvailableToReserveFundMovement(); }
            });
        }

        return actions;
    };

    if (query.isLoading) {
        return (
            <Fragment>
                <Backdrop
                    sx={(theme) => ({ color: "#fff", zIndex: theme.zIndex.drawer + 1 })}
                    open={query.isLoading}
                //onClick={handleClose}
                >
                    <CircularProgress color="inherit" />
                </Backdrop>
            </Fragment>
        );
    }
    else {

        return (
        <Fragment>
            <Box sx={{ flexGrow: 1, p: 4 }}>
                <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mb: 2 }}>
                    <Typography variant="h4" gutterBottom>
                        Contract details: {query.data.label}
                    </Typography>
                    <Badge badgeContent={getAvailableActions().length} color="primary">
                        <Button
                            variant="outlined"
                            endIcon={<MoreVertIcon />}
                            onClick={handleOpenActionsMenu}
                            disabled={getAvailableActions().length === 0}
                        >
                            Actions
                        </Button>
                    </Badge>
                </Box>
                <Divider sx={{ mb: 4 }} />
                <Grid2 container spacing={4}>

                    {/* Contenido principal del contrato */}
                    <Grid2 size={12}>

                        {/* Tarjeta de información general */}
                        <Card sx={{ mb: 4, borderRadius: 2, boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}>
                            <CardContent>
                                <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', mb: 2 }}>
                                    General Contract Information
                                </Typography>
                                <Grid2 container spacing={3}>
                                    {/* Identificador del contrato */}
                                    {query.data.initialized && (
                                        <>
                                            <Grid2 size={12}>
                                                <Typography variant="subtitle2" color="textSecondary">Contract Identifier</Typography>
                                                <Typography variant="body1" fontWeight="medium">{query.data.address}</Typography>
                                            </Grid2>
                                            <Grid2 size={12}>
                                                <Divider sx={{ my: 2 }} />
                                            </Grid2>
                                        </>
                                    )}

                                    {/* Resto de la información general */}
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Issuer company</Typography>
                                        <Typography variant="body1" fontWeight="medium">{query.data.issuer}</Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Status</Typography>
                                        <Box sx={{ display: 'flex', alignItems: 'center', gap: 0.5 }}>
                                            <Typography variant="body1" fontWeight="medium">{query.data.status}</Typography>
                                            {query.data.status === 'ACTIVE' && (<CheckCircleIcon sx={{ color: 'success.main', fontSize: '1.2rem' }} />)}
                                            {query.data.status === 'APPROVED' && (<VerifiedIcon sx={{ color: 'info.main', fontSize: '1.2rem' }} />)}
                                            {query.data.status === 'PAUSED' && (<PauseCircleIcon sx={{ color: 'warning.main', fontSize: '1.2rem' }} />)}
                                            {query.data.status === 'REVIEWING' && (<PendingIcon sx={{ color: 'grey.500', fontSize: '1.2rem' }} />)}
                                        </Box>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>

                                        {query.data.status === 'ACTIVE' && (
                                            <>
                                                <Typography variant="subtitle2" color="textSecondary">Started At</Typography>
                                                <Typography variant="body1" fontWeight="medium">{query.data.lastResumedAt ?? query.data.initializedAt}</Typography>
                                            </>
                                        )}
                                        {query.data.status === 'APPROVED' && (
                                            <>
                                                <Typography variant="subtitle2" color="textSecondary">Approved At</Typography>
                                                <Typography variant="body1" fontWeight="medium">{query.data.approvedAt}</Typography>
                                            </>
                                        )}
                                        {query.data.status === 'PAUSED' && (
                                            <>
                                                <Typography variant="subtitle2" color="textSecondary">Paused At</Typography>
                                                <Typography variant="body1" fontWeight="medium">{query.data.lastPausedAt}</Typography>
                                            </>
                                        )}
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Months to claim earnings</Typography>
                                        <Typography variant="body1" fontWeight="medium">{query.data.claimMonths === 0 ? "Claim available" : query.data.claimMonths}</Typography>
                                    </Grid2>
                                </Grid2>
                            </CardContent>
                        </Card>

                        {/* Tarjeta de detalles financieros */}
                        <Card sx={{ mb: 4, borderRadius: 2, boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}>
                            <CardContent>
                                <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', mb: 2 }}>
                                    Financial Details
                                </Typography>
                                <Grid2 container spacing={3}>
                                    {/* Información de fondos */}
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Received Funds</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.fundsReceived, query.data.tokenContract)} 
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Goal</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.goal, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Available Funds</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.available, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>

                                    {/* Detalles del token */}
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Deposit Token</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {query.data.tokenContract.name} ({query.data.tokenContract.code})
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Rate</Typography>
                                        <Typography variant="body1" fontWeight="medium">{query.data.rate}%</Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Minimum deposit</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {query.data.minPerInvestment ? formatCurrencyFromValueAndTokenContract(query.data.minPerInvestment, query.data.tokenContract) : 'Not specified yet'}
                                        </Typography>
                                    </Grid2>

                                    {/* Otros balances */}
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Reserved Funds</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.reserveFund, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Used Funds</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.projectWithdrawals, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Payments sent</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.payments, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Reserve fund contributions</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.reserveFundContributions, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>
                                    <Grid2 size={{ xs: 12, sm: 6, md: 4 }}>
                                        <Typography variant="subtitle2" color="textSecondary">Available to reserve movements</Typography>
                                        <Typography variant="body1" fontWeight="medium">
                                            {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.availableToReserveMovements, query.data.tokenContract)}
                                        </Typography>
                                    </Grid2>
                                </Grid2>
                                <Divider sx={{ my: 3 }} />
                                {/* Barra de progreso de fondos */}
                                <Typography variant="subtitle2" color="textSecondary" sx={{ mb: 1 }}>Funds raised</Typography>
                                <Box sx={{ display: 'flex', alignItems: 'center', mb: 1 }}>
                                    <LinearProgress
                                        variant="determinate"
                                        value={query.data.contractBalance.percentajeFundsReceived}
                                        sx={{ flexGrow: 1, mr: 2, height: 10, borderRadius: 1 }}
                                    />
                                    <Typography variant="body1" fontWeight="medium">
                                        {query.data.contractBalance.percentajeFundsReceived}%
                                    </Typography>
                                </Box>
                                <Typography variant="caption" color="textSecondary">
                                    {formatCurrencyFromValueAndTokenContract(query.data.contractBalance.fundsReceived, query.data.tokenContract)} 
                                    / {formatCurrencyFromValueAndTokenContract(query.data.goal, query.data.tokenContract)}
                                </Typography>
                            </CardContent>
                        </Card>

                        {/* Tarjeta para la descripción y el documento */}
                        <Card sx={{ borderRadius: 2, boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}>
                            <CardContent>
                                <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', mb: 2 }}>
                                    Project Description
                                </Typography>
                                <Typography variant="body1" align="justify" sx={{ mb: 3 }}>
                                    {query.data.description}
                                </Typography>
                                <Button
                                    variant="contained"
                                    color="primary"
                                    onClick={handleReadProjectDocument}
                                    sx={{ mt: 2 }}
                                >
                                    Read the document
                                </Button>
                            </CardContent>
                        </Card>
                    </Grid2>
                </Grid2>

                {/* Menú de acciones */}
                <Menu
                    anchorEl={anchorElActions}
                    open={openActionsMenu}
                    onClose={handleCloseActionsMenu}
                    anchorOrigin={{
                        vertical: 'bottom',
                        horizontal: 'right',
                    }}
                    transformOrigin={{
                        vertical: 'top',
                        horizontal: 'right',
                    }}
                >
                    {getAvailableActions().map((action) => (
                        <MenuItem key={action.id} onClick={action.onClick}>
                            <Box sx={{ display: 'flex', alignItems: 'center', gap: 1.5 }}>
                                {action.icon}
                                <Typography>{action.label}</Typography>
                            </Box>
                        </MenuItem>
                    ))}
                </Menu>
            </Box>

            {/* Modal para el visor de PDF (sin cambios, lo puedes mantener tal cual) */}
            <Dialog
                open={openPdfModal || downloadingPdf}
                onClose={handleClosePdfModal}
                fullScreen
            >
                <DialogTitle sx={{ padding: '16px 24px', borderBottom: '1px solid #e0e0e0' }}>
                    <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <Typography variant="h6" component="span" sx={{ fontWeight: 'bold' }}>Project document for: {contract?.label} </Typography>
                        <IconButton edge="end" color="inherit" onClick={handleClosePdfModal} aria-label="close">
                            <CloseIcon />
                        </IconButton>
                    </Box>
                </DialogTitle>
                <DialogContent dividers sx={{ p: 0, display: 'flex', flexDirection: 'column' }} >
                    {pdfUrl ? (
                        <PdfViewer pdfUrl={pdfUrl} />
                    ) : (
                        <Box
                            sx={{
                                flexGrow: 1,
                                display: 'flex',
                                justifyContent: 'center',
                                alignItems: 'center',
                                flexDirection: 'column',
                                gap: 2,
                                p: 2,
                            }}
                        >
                            <CircularProgress size={60} thickness={4} />
                            <Typography variant="h6" color="text.secondary">
                                Loading document...
                            </Typography>
                        </Box>
                    )}
                </DialogContent>
            </Dialog>

            {openApproveModal && contractSelected && <ApproveContractModal
                openApproveModal={openApproveModal} 
                handleModalClose={handleCloseApproveModal} 
                handleApprovalFinished={handleApprovalFinished} 
                contractToApprove={contractSelected} 
            /> }

            {openActivationModal && contractSelected && <ActivateContractModal
                openActivateModal={openActivationModal}
                handleModalClose={handleCloseActivationModal}
                handleActivationFinished={handleActivationFinished}
                contractToActivate={contractSelected}
            />}

            {openModalToRequestWithdrawal && contractSelected && <CreateWithdrawalRequestModal
                open={openModalToRequestWithdrawal}
                contract={contractSelected}
                onClose={handleCloseRequestWithdrawalModal}
            /> }

            {openModalToRequestReserveFundContribution && contractSelected && <CreateReserveFundContributionModal
                open={openModalToRequestReserveFundContribution}
                contract={contractSelected}
                onClose={handleCloseReserveFundContributionModal}
            /> }

            {openModalToRequestAvailableToReserveFundMovement && contractSelected && <CreateAvailableToReserveMovementModal
                open={openModalToRequestAvailableToReserveFundMovement}
                contract={contractSelected}
                onClose={handleCloseAvailableToReserveFundMovementModal}
            /> }

            {openModalToPauseContract && contractSelected && <PauseContractInvestmentsModal 
                openPauseInvesmentsModal={openModalToPauseContract}
                contractToPause={contractSelected}
                handleModalClose={handleClosePauseContractModal}
                handlePausingFinished={handlePauseContractFinished}
            />}

            {openModalToResumeContract && contractSelected && <ResumeContractInvestmentsModal 
                openResumeInvesmentsModal={openModalToResumeContract}
                contractToResume={contractSelected}
                handleModalClose={handleCloseResumeContractModal}
                handleResumingFinished={handleResumeContractFinished}
            />}

        </Fragment>
        )
    };
}