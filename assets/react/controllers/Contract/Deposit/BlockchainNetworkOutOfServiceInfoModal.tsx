import { Box, Button, Modal, Typography } from "@mui/material";
import WarningAmberIcon from '@mui/icons-material/WarningAmber';
import { FC } from "react";
import { StyledModalBox } from "../../Theme/Styled/Modal";

export interface BlockchainNetworkOutOfServiceInfoModalProps {
    openModalHelp: boolean,
    handleCloseModalHelp: () => void
}

const BlockchainNetworkOutOfServiceInfoModal: FC<BlockchainNetworkOutOfServiceInfoModalProps> = ({openModalHelp, handleCloseModalHelp}) => {
    return (
        <Modal open={openModalHelp} onClose={handleCloseModalHelp}>
            <StyledModalBox width={600} useFlex={true}>
                <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                    <WarningAmberIcon sx={{ color: 'red', mr: 1, fontSize: 30 }} />
                    <Typography variant="h6" component="h2" sx={{ color: 'red' }}>
                        Blockchain out of service
                    </Typography>
                </Box>
                <Typography sx={{ mt: 2 }}>
                    It seems that the blockchain network is currently out of service. Please, try it later.
                </Typography>
                <Button onClick={handleCloseModalHelp} sx={{ mt: 2 }}>
                    Close
                </Button>
            </StyledModalBox>
        </Modal>
    )
}

export default BlockchainNetworkOutOfServiceInfoModal;