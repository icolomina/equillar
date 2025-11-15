// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { 
    Box, 
    Button, 
    Collapse,
    Divider,
    IconButton,
    Modal, 
    Typography, 
    useMediaQuery, 
    useTheme 
} from "@mui/material";
import { BlockchainError, BlockchainErrorType } from "../model/error";
import WarningAmberIcon from '@mui/icons-material/WarningAmber';
import WifiOffIcon from '@mui/icons-material/WifiOff';
import CloseIcon from '@mui/icons-material/Close';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import ExpandLessIcon from '@mui/icons-material/ExpandLess';
import OpenInNewIcon from '@mui/icons-material/OpenInNew';
import { useState } from "react";

interface BlockchainErrorModalProps {
    open: boolean;
    error: BlockchainError | null;
    onClose: () => void;
}

export default function BlockchainErrorModal({ open, error, onClose }: BlockchainErrorModalProps) {
    const theme = useTheme();
    const isMobile = useMediaQuery(theme.breakpoints.down('sm'));
    const [showDetails, setShowDetails] = useState(false);

    if (!error) {
        return null;
    }

    const isContractError = error.error === BlockchainErrorType.CONTRACT_EXECUTION_FAILED;
    const isNetworkError = error.error === BlockchainErrorType.BLOCKCHAIN_NETWORK_ERROR;

    const getTitle = () => {
        if (isContractError) {
            return "Contract Execution Failed";
        }
        return "Blockchain Network Error";
    };

    const getIcon = () => {
        if (isContractError) {
            return <WarningAmberIcon sx={{ fontSize: 60, color: 'warning.main' }} />;
        }
        return <WifiOffIcon sx={{ fontSize: 60, color: 'error.main' }} />;
    };

    const getColor = () => {
        return isContractError ? 'warning.main' : 'error.main';
    };

    const getStellarExplorerUrl = () => {
        if (!error.transaction_hash) return null;
        // Asumiendo Testnet, ajustar según tu configuración
        return `https://stellar.expert/explorer/testnet/tx/${error.transaction_hash}`;
    };

    return (
        <Modal
            open={open}
            onClose={onClose}
            aria-labelledby="blockchain-error-modal-title"
            aria-describedby="blockchain-error-modal-description"
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
                    position: 'relative',
                }}
            >
                {/* Close button */}
                <IconButton
                    aria-label="close"
                    onClick={onClose}
                    sx={{
                        position: 'absolute',
                        right: 8,
                        top: 8,
                        color: 'grey.500',
                    }}
                >
                    <CloseIcon />
                </IconButton>

                {/* Icon */}
                <Box sx={{ mb: 2 }}>
                    {getIcon()}
                </Box>

                {/* Title */}
                <Typography 
                    id="blockchain-error-modal-title" 
                    variant="h5" 
                    component="h2" 
                    mb={2} 
                    textAlign="center"
                    sx={{ color: getColor(), fontWeight: 'bold' }}
                >
                    {getTitle()}
                </Typography>

                {/* Main Error Message */}
                <Typography 
                    id="blockchain-error-modal-description"
                    variant="body1" 
                    sx={{ mb: 3, textAlign: 'center', color: 'text.primary' }}
                >
                    {error.message}
                </Typography>

                {/* Details Section - Collapsible */}
                <Box sx={{ width: '100%', mb: 2 }}>
                    <Button
                        onClick={() => setShowDetails(!showDetails)}
                        endIcon={showDetails ? <ExpandLessIcon /> : <ExpandMoreIcon />}
                        sx={{ 
                            width: '100%', 
                            justifyContent: 'space-between',
                            textTransform: 'none',
                            color: 'text.secondary'
                        }}
                    >
                        Technical Details
                    </Button>
                    
                    <Collapse in={showDetails}>
                        <Box 
                            sx={{ 
                                mt: 2, 
                                p: 2, 
                                bgcolor: 'grey.100', 
                                borderRadius: 1,
                                border: 1,
                                borderColor: 'grey.300'
                            }}
                        >
                            {error.contract_id && (
                                <Typography variant="body2" sx={{ mb: 1 }}>
                                    <strong>Contract ID:</strong> {error.contract_id}
                                </Typography>
                            )}
                            
                            {error.transaction_hash && (
                                <Box sx={{ mb: 1 }}>
                                    <Typography variant="body2" component="span">
                                        <strong>Transaction Hash:</strong>{' '}
                                    </Typography>
                                    <Typography 
                                        variant="body2" 
                                        component="span"
                                        sx={{ 
                                            wordBreak: 'break-all',
                                            fontFamily: 'monospace',
                                            fontSize: '0.85rem'
                                        }}
                                    >
                                        {error.transaction_hash}
                                    </Typography>
                                </Box>
                            )}
                            
                            <Typography variant="body2" sx={{ color: 'text.secondary', fontSize: '0.75rem' }}>
                                <strong>Timestamp:</strong> {new Date(error.timestamp).toLocaleString()}
                            </Typography>
                        </Box>
                    </Collapse>
                </Box>

                <Divider sx={{ width: '100%', mb: 2 }} />

                {/* Action Buttons */}
                <Box sx={{ display: 'flex', gap: 2, justifyContent: 'center', width: '100%' }}>
                    {error.transaction_hash && (
                        <Button
                            variant="outlined"
                            color="primary"
                            endIcon={<OpenInNewIcon />}
                            onClick={() => {
                                const url = getStellarExplorerUrl();
                                if (url) window.open(url, '_blank');
                            }}
                            sx={{ textTransform: 'none' }}
                        >
                            View on Explorer
                        </Button>
                    )}
                    
                    <Button
                        variant="contained"
                        color="primary"
                        onClick={onClose}
                    >
                        Close
                    </Button>
                </Box>

                {/* Help Text */}
                {isNetworkError && (
                    <Typography 
                        variant="caption" 
                        sx={{ 
                            mt: 2, 
                            textAlign: 'center', 
                            color: 'text.secondary',
                            fontStyle: 'italic'
                        }}
                    >
                        This error is usually temporary. Please try again in a few moments.
                    </Typography>
                )}
            </Box>
        </Modal>
    );
}
