/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Fragment, useEffect, useState } from "react";
import { useApi } from "../../hooks/ApiHook";
import { useApiRoutes } from "../../hooks/ApiRoutesHook";
import { Box, Button, CircularProgress, Modal, Typography } from "@mui/material";

export default function ActivateContractModal({ openActivateModal, handleModalClose, handleActivationFinished, contractToActivate }) {

    const { callPatch } = useApi();
    const apiRoutes = useApiRoutes();

    const [activating, setActivating] = useState<boolean>(false);
    const [active, setActive] = useState<boolean>(false);
    const [errorActivating, setErrorActivating] = useState<boolean>(false);

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
        if (openActivateModal) {
            setActive(false);
            setActivating(false);
        }
    }, [openActivateModal]);

    const handleActivate = () => {
        const contractId = contractToActivate.id;
        setActivating(true);
        callPatch(apiRoutes.startContract(contractId), {})
            .then(
                async () => {
                    setActivating(false);
                    setActive(true);
                }
            ).catch(
                (reason: any) => {
                    console.log(reason);
                    handleCloseAndNotify();
                }
            )
        ;
    }

    const handleCloseAndNotify = () => {
        const hasBeenActivated = active;
        setActive(false); 
        if (hasBeenActivated) {
            handleActivationFinished();
        }

        handleModalClose();
    };

    return (
        <Fragment>
            <Modal
                open={openActivateModal}
                // Cuando está aprobando, no permitimos cerrar el modal haciendo clic fuera o con Esc
                onClose={activating ? undefined : handleModalClose}
                aria-labelledby="confirmation-modal-activation_confirmation"
                aria-describedby="confirmation-modal-description"
            >
                <Box sx={activateModalStyle}>
                    {/* ✨ Renderizado condicional del contenido del modal */}
                    {activating ? (
                        // Contenido durante la aprobación (Loader)
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <CircularProgress size={50} sx={{ mb: 2 }} />
                            <Typography variant="h6">Activating contract</Typography>
                            <Typography variant="body2" color="textSecondary">Please, wait a moment ...</Typography>
                        </Box>
                    ) : active ? (
                        // Contenido después de la aprobación exitosa
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'success.main' }}>
                                Contract activated
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                The project <strong>{contractToActivate?.label}</strong> has been successfully activated.
                                Now the users can invest on this project
                            </Typography>
                            <Button variant="contained" color="primary" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : errorActivating ? (
                        // Contenido si hubo un error en la aprobación
                        <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', p: 3 }}>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2, color: 'error.main' }}>
                                Activating process error
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                An error ocurred while trying to activate the contract <strong>{contractToActivate?.label}</strong>. Please, try it later or contact to the adminisrator.
                            </Typography>
                            <Button variant="contained" color="error" onClick={handleCloseAndNotify}>
                                Close
                            </Button>
                        </Box>
                    ) : (
                        // Contenido inicial del modal (confirmación antes de aprobar)
                        <Fragment>
                            <Typography id="confirmation-modal-title" variant="h6" component="h2" sx={{ mb: 2 }}>
                                Activation confirmation
                            </Typography>
                            <Typography id="confirmation-modal-description" sx={{ mb: 3 }}>
                                You are going to activate the contract: <strong>{contractToActivate?.label}</strong>.
                                Remember that, once active, the users will be able to invest on this project.
                            </Typography>
                            <Box sx={{ display: 'flex', justifyContent: 'center', gap: 2 }}>
                                <Button variant="contained" color="primary" onClick={handleActivate}>
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