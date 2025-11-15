// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Fragment, useEffect, useState } from "react";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import { Box, Button, CircularProgress, Modal, Typography } from "@mui/material";

export default function ResumeContractInvestmentsModal({ openResumeInvesmentsModal, handleModalClose, handleResumingFinished, contractToResume}) {

    const { callPatch } = useApi();
    const apiRoutes = useApiRoutes();

    const [resuming, setResumimg] = useState<boolean>(false);
    const [resumed, setResumed] = useState<boolean>(false);
    const [errorResuming, setErrorResuming] = useState<boolean>(false);

    const activateModalStyle = {
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
        if (openResumeInvesmentsModal) {
            setResumed(false);
            setResumimg(false);
        }
    }, [openResumeInvesmentsModal]);

    const handleResume = () => {
        const contractId = contractToResume.id;
        setResumimg(true);
        callPatch(apiRoutes.resumeContract(contractId), {}).then(
            async () => {
                setResumimg(false);
                setResumed(true);
            }
        )
    }

    const handleCloseAndNotify = () => {
        const hasBeenResumed = resumed;
        setResumed(false); 
        if (hasBeenResumed) {
            handleResumingFinished();
        }

        handleModalClose();
    };

    return (
        <Fragment>
            <Modal
                open={openResumeInvesmentsModal}
                // Cuando está aprobando, no permitimos cerrar el modal haciendo clic fuera o con Esc
                onClose={resumed ? undefined : handleModalClose}
                aria-labelledby="confirmation-modal-activation_confirmation"
                aria-describedby="confirmation-modal-description"
            >
                <Box sx={activateModalStyle}>
                    {/* ✨ Renderizado condicional del contenido del modal */}
                    {resuming ? (
                        // Contenido durante la aprobación (Loader)
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <CircularProgress size={50} sx={{ mb: 2 }} />
                            <Typography variant="h6">Resuming contract</Typography>
                            <Typography variant="body2" color="textSecondary">Please, wait a moment ...</Typography>
                        </Box>
                    ) : resumed ? (
                        // Contenido después de la aprobación exitosa
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'success.main' }}>
                                Contract paused
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                The project <strong>{contractToResume?.label}</strong> has been successfully resumed.
                                Now users can invest again.
                            </Typography>
                            <Button variant="contained" color="primary" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : errorResuming ? (
                        // Contenido si hubo un error en la aprobación
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'error.main' }}>
                                Resuming process error
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                An error ocurred while trying to reume the contract <strong>{contractToResume?.label}</strong>. Please, try it later or contact to the adminisrator.
                            </Typography>
                            <Button variant="contained" color="error" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : (
                        // Contenido inicial del modal (confirmación antes de aprobar)
                        <Fragment>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2 }}>
                                Resuming confirmation
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                You are going to resume the contract: <strong>{contractToResume?.label}</strong>.
                                Remember that, once resumed, the users will be able to invest on this project again.
                            </Typography>
                            <Box sx={{ display: 'flex', justifyContent: 'center', gap: 2 }}>
                                <Button variant="contained" color="primary" onClick={handleResume}>
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