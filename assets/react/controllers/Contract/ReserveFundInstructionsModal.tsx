// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Box, Button, Card, CardContent, Dialog, DialogContent, DialogTitle, IconButton, Typography } from "@mui/material";
import CloseIcon from '@mui/icons-material/Close';
import AccountBalanceWalletIcon from '@mui/icons-material/AccountBalanceWallet';
import PaymentsIcon from '@mui/icons-material/Payments';
import { ContractOutput } from "../../model/contract";
import { formatCurrencyFromValueAndTokenContract } from "../../utils/currency";

interface ReserveFundInstructionsModalProps {
    open: boolean;
    onClose: () => void;
    contract: ContractOutput;
}

export default function ReserveFundInstructionsModal({ open, onClose, contract }: ReserveFundInstructionsModalProps) {
    return (
        <Dialog
            open={open}
            onClose={onClose}
            maxWidth="md"
            fullWidth
        >
            <DialogTitle>
                <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <Typography variant="h6" component="span" sx={{ fontWeight: 'bold' }}>
                        How to add funds to the reserve
                    </Typography>
                    <IconButton edge="end" color="inherit" onClick={onClose} aria-label="close">
                        <CloseIcon />
                    </IconButton>
                </Box>
            </DialogTitle>
            <DialogContent dividers>
                <Box sx={{ display: 'flex', flexDirection: 'column', gap: 3 }}>
                    <Typography variant="body1" color="text.secondary">
                        The contract requires <strong>{formatCurrencyFromValueAndTokenContract(contract?.requiredReserveFunds || 0, contract?.tokenContract)}</strong> to be added to the reserve fund to ensure investor payments. You have two options:
                    </Typography>

                    <Card sx={{ borderLeft: '4px solid', borderColor: 'primary.main', backgroundColor: 'background.paper' }}>
                        <CardContent>
                            <Box sx={{ display: 'flex', alignItems: 'flex-start', gap: 2 }}>
                                <AccountBalanceWalletIcon color="primary" sx={{ fontSize: 40, mt: 0.5 }} />
                                <Box sx={{ flex: 1 }}>
                                    <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold' }}>
                                        Option 1: Reserve Fund Contribution
                                    </Typography>
                                    <Typography variant="body2" color="text.secondary" paragraph>
                                        Make an external contribution to the reserve fund. This option allows you to transfer funds from an external account directly to the contract's reserve fund.
                                    </Typography>
                                    <Typography variant="body2" sx={{ fontWeight: 'medium', mb: 1 }}>
                                        How to do it:
                                    </Typography>
                                    <Typography variant="body2" color="text.secondary">
                                        1. Click on the <strong>"Actions"</strong> button at the top of the page<br/>
                                        2. Select <strong>"Reserve fund contribution"</strong><br/>
                                        3. Follow the instructions to complete the transfer
                                    </Typography>
                                </Box>
                            </Box>
                        </CardContent>
                    </Card>

                    {contract?.contractBalance?.available > 0 ? (
                        <Card sx={{ borderLeft: '4px solid', borderColor: 'success.main', backgroundColor: 'background.paper' }}>
                            <CardContent>
                                <Box sx={{ display: 'flex', alignItems: 'flex-start', gap: 2 }}>
                                    <PaymentsIcon color="success" sx={{ fontSize: 40, mt: 0.5 }} />
                                    <Box sx={{ flex: 1 }}>
                                        <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', color: 'success.main' }}>
                                            Option 2: Move funds to the reserve (Recommended)
                                        </Typography>
                                        <Typography variant="body2" color="text.secondary" paragraph>
                                            Move available funds from the contract to the reserve fund. This is a simpler option that uses the funds already in the contract.
                                        </Typography>
                                        <Typography variant="body2" sx={{ fontWeight: 'medium', mb: 1 }}>
                                            Available funds: <strong>{formatCurrencyFromValueAndTokenContract(contract.contractBalance.available, contract.tokenContract)}</strong>
                                        </Typography>
                                        <Typography variant="body2" sx={{ fontWeight: 'medium', mb: 1 }}>
                                            How to do it:
                                        </Typography>
                                        <Typography variant="body2" color="text.secondary">
                                            1. Click on the <strong>"Actions"</strong> button at the top of the page<br/>
                                            2. Select <strong>"Move funds to the reserve"</strong><br/>
                                            3. Specify the amount to move and confirm
                                        </Typography>
                                    </Box>
                                </Box>
                            </CardContent>
                        </Card>
                    ) : (
                        <Card sx={{ borderLeft: '4px solid', borderColor: 'grey.400', backgroundColor: 'grey.50' }}>
                            <CardContent>
                                <Box sx={{ display: 'flex', alignItems: 'flex-start', gap: 2 }}>
                                    <PaymentsIcon sx={{ fontSize: 40, mt: 0.5, color: 'grey.400' }} />
                                    <Box sx={{ flex: 1 }}>
                                        <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', color: 'text.disabled' }}>
                                            Option 2: Move funds to the reserve (Not available)
                                        </Typography>
                                        <Typography variant="body2" color="text.secondary">
                                            This option is not available because the contract has not received any contributions yet. Once investors make contributions, you will be able to move funds from available to reserve.
                                        </Typography>
                                    </Box>
                                </Box>
                            </CardContent>
                        </Card>
                    )}

                    <Box sx={{ mt: 2, p: 2, backgroundColor: 'info.lighter', borderRadius: 1 }}>
                        <Typography variant="body2" color="info.dark">
                            <strong>Note:</strong> It's important to maintain an adequate reserve fund to ensure timely payments to investors and avoid contract blocking.
                        </Typography>
                    </Box>
                </Box>
            </DialogContent>
            <Box sx={{ p: 2, display: 'flex', justifyContent: 'flex-end' }}>
                <Button onClick={onClose} variant="contained">
                    Got it
                </Button>
            </Box>
        </Dialog>
    );
}
