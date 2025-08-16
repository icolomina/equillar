import { Box, Button, CircularProgress, Modal, Typography } from "@mui/material";
import { Fragment } from "react/jsx-runtime";
import { useApi } from "../../hooks/ApiHook";
import { useEffect, useState } from "react";
import { ApproveContractPath } from "../../services/Api/Investment/ApiRoutes";
import { sprintf } from "sprintf-js";

export default function ApproveContractModal({ openApproveModal, handleModalClose, handleApprovalFinished, contractToApprove }) {

    const { callPatch } = useApi();

    const [approving, setApproving] = useState<boolean>(false);
    const [approved, setApproved] = useState<boolean>(false);
    const [errorApproving, setErrorApproving] = useState<boolean>(false);

    const approveModalStyle = {
        position: 'absolute' as 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        width: 400,
        bgcolor: 'background.paper',
        border: '2px solid #000',
        boxShadow: 24,
        p: 4,
        textAlign: 'center',
        display: 'flex', // Usamos flexbox para centrar el contenido fácilmente
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
    };

    useEffect(() => {
        if (openApproveModal) {
            setApproved(false);
            setApproving(false);
        }
    }, [openApproveModal]);

    const handleApprove = () => {
        const contractId = contractToApprove.id;
        setApproving(true);
        callPatch(sprintf(ApproveContractPath, contractId), {}).then(
            async () => {
                setApproving(false);
                setApproved(true);
            }
        )
    }

    const handleCloseAndNotify = () => {
        const hasBeenApproved = approved;
        setApproved(false); // Resetear estado de éxito para la próxima vez
        if (hasBeenApproved) {
            handleApprovalFinished(); // Llama al callback del padre
        }

        handleModalClose(); // Cierra el modal
    };

    return (
        <Fragment>
            <Modal
                open={openApproveModal}
                // Cuando está aprobando, no permitimos cerrar el modal haciendo clic fuera o con Esc
                onClose={approving ? undefined : handleModalClose}
                aria-labelledby="confirmation-modal-approve_confirmation"
                aria-describedby="confirmation-modal-description"
            >
                <Box sx={approveModalStyle}>
                    {/* ✨ Renderizado condicional del contenido del modal */}
                    {approving ? (
                        // Contenido durante la aprobación (Loader)
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <CircularProgress size={50} sx={{ mb: 2 }} />
                            <Typography variant="h6">Approving contract</Typography>
                            <Typography variant="body2" color="textSecondary">Please, wait a moment ...</Typography>
                        </Box>
                    ) : approved ? (
                        // Contenido después de la aprobación exitosa
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'success.main' }}>
                                Contract approved
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                The project <strong>{contractToApprove?.label}</strong> has been successfully approved.
                                Now the issuer can enable the contract.
                            </Typography>
                            <Button variant="contained" color="primary" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : errorApproving ? (
                        // Contenido si hubo un error en la aprobación
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'error.main' }}>
                                Approving error
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                An error ocurred while trying to approve the contract <strong>{contractToApprove?.label}</strong>. Please, try it later or contact to the adminisrator.
                            </Typography>
                            <Button variant="contained" color="error" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : (
                        // Contenido inicial del modal (confirmación antes de aprobar)
                        <Fragment>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2 }}>
                                Approval confirmation
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                You are going to approve the contract: <strong>{contractToApprove?.label}</strong>.
                                Remember that, once approved, the company will be able to enable it and start receiving funds.
                            </Typography>
                            <Box sx={{ display: 'flex', justifyContent: 'center', gap: 2 }}>
                                <Button variant="contained" color="primary" onClick={handleApprove}>
                                    Ok. Go on
                                </Button>
                                <Button variant="outlined" onClick={handleCloseAndNotify}>
                                    Not yet
                                </Button>
                            </Box>
                        </Fragment>
                    )}
                </Box>
            </Modal>
        </Fragment>
    )
}