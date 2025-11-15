// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Box, Button, Modal, Typography } from "@mui/material";
import WarningAmberIcon from '@mui/icons-material/WarningAmber';
import { FC } from "react";
import { StyledModalBox } from "../../Theme/Styled/Modal";

export interface ConnectWalletToSendDepositInfoModalProps {
    openModalHelp: boolean,
    handleCloseModalHelp: () => void
}

const ConnectWalletToSendDepositInfoModal: FC<ConnectWalletToSendDepositInfoModalProps> = ({openModalHelp, handleCloseModalHelp}) => {
    return (
        <Modal open={openModalHelp} onClose={handleCloseModalHelp}>
            <StyledModalBox width={600} useFlex={true} >
                <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                    <WarningAmberIcon sx={{ color: 'orange', mr: 1, fontSize: 30 }} /> {/* Icono de advertencia */}
                    <Typography variant="h6" component="h2" sx={{ color: 'orange' }}>
                        Connect your wallet before starting
                    </Typography>
                </Box>
                <Typography sx={{ mt: 2 }}>
                    To invest, you must connect your wallet using the button in the top right corner. Once connected, we will query your project's token balance.
                    If you don't have enough balance, a warning will appear, and the investment button will remain disabled.
                </Typography>
                <Button onClick={handleCloseModalHelp} sx={{ mt: 2 }}>
                    Close
                </Button>
            </StyledModalBox>
        </Modal>
    )
}

export default ConnectWalletToSendDepositInfoModal;