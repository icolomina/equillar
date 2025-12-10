
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { ContractOutput } from "../../../model/contract";
import { Box, Button, Modal, Typography, useMediaQuery, useTheme } from "@mui/material";
import IconButton from '@mui/material/IconButton';
import ContentCopyIcon from '@mui/icons-material/ContentCopy';

interface ReserveFundContributionModalProps {
    open: boolean;
    contract: ContractOutput;
    onClose: () => void;
}

export default function CreateReserveFundContributionModal(props: ReserveFundContributionModalProps) {

    const theme = useTheme();
    const isMobile = useMediaQuery(theme.breakpoints.down('sm'));

    const handleCopyAddress = () => {
        navigator.clipboard.writeText(props.contract.muxedAccount);
    };

    return (
        <Modal
            open={props.open}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
            sx={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
            }}
        >
            <Box
                sx={{
                    bgcolor: 'background.paper',
                    borderRadius: 1,
                    boxShadow: 24,
                    p: 4,
                    width: isMobile ? '90%' : 600,
                    maxWidth: 600,
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                }}
            >
                <Typography id="modal-modal-title" variant="h5" component="h2" mb={2} textAlign="center">
                    Reserve Fund Contribution
                </Typography>

                <Typography variant="body1" sx={{ mt: 2, mb: 3, textAlign: 'center' }}>
                    To make a contribution to the reserve fund, simply transfer the desired amount to the following address:
                </Typography>

                <Box sx={{
                    display: 'flex',
                    alignItems: 'center',
                    mt: 1,
                    p: 2,
                    bgcolor: 'grey.200',
                    borderRadius: 1,
                    width: '100%',
                }}>
                    <Typography variant="body2" sx={{
                        flexGrow: 1,
                        wordBreak: 'break-all',
                        textAlign: 'left',
                        fontFamily: 'monospace',
                    }}>
                        {props.contract.muxedAccount}
                    </Typography>
                    <IconButton
                        aria-label="copy address"
                        onClick={handleCopyAddress}
                        sx={{ ml: 1, color: 'primary.main' }}
                    >
                        <ContentCopyIcon />
                    </IconButton>
                </Box>

                <Typography variant="caption" sx={{ mt: 2, textAlign: 'center', color: 'text.secondary' }}>
                    Any transfer to this address will be automatically registered as a contribution to the reserve fund.
                </Typography>

                <Button variant="outlined" color="primary" onClick={() => props.onClose()} sx={{ mt: 3 }}>
                    Close
                </Button>
            </Box>
        </Modal>
    );
}