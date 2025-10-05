/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Box, Button, Modal, Typography } from "@mui/material";
import { FC } from "react";
import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import { StyledModalBox } from "../../Theme/Styled/Modal";

export interface SentDepositResultInfoModalProps {
    openModal: boolean,
    handleClose: () => void,
    handleNavigateToPortfolio: () => void
}

const SentDepositResultInfoModal: FC<SentDepositResultInfoModalProps> = ({ openModal, handleClose, handleNavigateToPortfolio }) => {

    return (
        <Modal open={openModal} aria-labelledby="confirmation-modal-title" aria-describedby="confirmation-modal-description">
            <StyledModalBox width={400} useFlex={true}>
                <CheckCircleIcon sx={{ color: 'green', mr: 1, fontSize: 30 }} />
                <Typography variant="h6" component="h2" sx={{ color: 'green', mb: 2 }}>
                    Deposit received successfully {String.fromCodePoint(0x1F60A)}
                </Typography>
                <Typography sx={{ mb: 3 }}>
                    Your deposit has been received. You can view your investment details in your portfolio.
                </Typography>
                <Box sx={{ display: 'flex', justifyContent: 'center', gap: 2 }}>
                    <Button variant="contained" color="primary" onClick={handleNavigateToPortfolio}>
                        Go to my portfolio
                    </Button>
                    <Button variant="outlined" onClick={handleClose}>
                        Close
                    </Button>
                </Box>
            </StyledModalBox>
        </Modal>
    )
}

export default SentDepositResultInfoModal;