/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Fragment, useEffect, useState } from "react";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import { Box, Button, CircularProgress, Modal, Typography } from "@mui/material";

export default function PauseContractInvestmentsModal({ openPauseInvesmentsModal, handleModalClose, handlePausingFinished, contractToPause}) {

    const { callPatch } = useApi();
    const apiRoutes = useApiRoutes();

    const [pausing, setPausing] = useState<boolean>(false);
    const [paused, setPaused] = useState<boolean>(false);
    const [errorPausing, setErrorPausing] = useState<boolean>(false);

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
        if (openPauseInvesmentsModal) {
            setPaused(false);
            setPausing(false);
        }
    }, [openPauseInvesmentsModal]);

    const handlePause = () => {
        const contractId = contractToPause.id;
        setPausing(true);
        callPatch(apiRoutes.pauseContract(contractId), {}).then(
            async () => {
                setPausing(false);
                setPaused(true);
            }
        )
    }

    const handleCloseAndNotify = () => {
        const hasBeenPaused = paused;
        setPaused(false); 
        if (hasBeenPaused) {
            handlePausingFinished();
        }

        handleModalClose();
    };

    return (
        <Fragment>
            <Modal
                open={openPauseInvesmentsModal}
                // Cuando está aprobando, no permitimos cerrar el modal haciendo clic fuera o con Esc
                onClose={pausing ? undefined : handleModalClose}
                aria-labelledby="confirmation-modal-activation_confirmation"
                aria-describedby="confirmation-modal-description"
            >
                <Box sx={activateModalStyle}>
                    {/* ✨ Renderizado condicional del contenido del modal */}
                    {pausing ? (
                        // Contenido durante la aprobación (Loader)
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <CircularProgress size={50} sx={{ mb: 2 }} />
                            <Typography variant="h6">Pausing contract</Typography>
                            <Typography variant="body2" color="textSecondary">Please, wait a moment ...</Typography>
                        </Box>
                    ) : paused ? (
                        // Contenido después de la aprobación exitosa
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'success.main' }}>
                                Contract paused
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                The project <strong>{contractToPause?.label}</strong> has been successfully paused.
                                You can resume it later
                            </Typography>
                            <Button variant="contained" color="primary" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : errorPausing ? (
                        // Contenido si hubo un error en la aprobación
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'error.main' }}>
                                Pausing process error
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                An error ocurred while trying to pause the contract <strong>{contractToPause?.label}</strong>. Please, try it later or contact to the adminisrator.
                            </Typography>
                            <Button variant="contained" color="error" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : (
                        // Contenido inicial del modal (confirmación antes de aprobar)
                        <Fragment>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2 }}>
                                Pausing confirmation
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                You are going to pause the contract: <strong>{contractToPause?.label}</strong>.
                                Remember that, once paused, the users will not be able to invest on this project.
                            </Typography>
                            <Box sx={{ display: 'flex', justifyContent: 'center', gap: 2 }}>
                                <Button variant="contained" color="primary" onClick={handlePause}>
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