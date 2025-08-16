import { Box, Card, CardContent, Chip, List, ListItem, ListItemIcon, ListItemText, Pagination, Typography } from "@mui/material";
import { UserContractCalendarItem } from "../../../../model/user";
import { formatCurrencyFromValueAndTokenContract } from "../../../../utils/currency";
import { TokenContract } from "../../../../model/token";

import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import AccessTimeIcon from '@mui/icons-material/AccessTime';
import { useMemo, useState } from "react";

interface UserContractPaymentsCalendarProps {
    paymentsCalendar: UserContractCalendarItem[]
    tokenContract: TokenContract
}

export default function UserContractPaymentsCalendar({ paymentsCalendar, tokenContract }: UserContractPaymentsCalendarProps) {

    const [page, setPage] = useState<number>(1);

    const { pageCount, currentItems } = useMemo(() => {
        const count = Math.ceil(paymentsCalendar.length / 6);
        const start = (page - 1) * 6;
        const end = start + 6;
        const items = paymentsCalendar.slice(start, end);

        return {
            pageCount: count,
            currentItems: items
        };
    }, [paymentsCalendar, page]);

    const handleChange = (_: any, value: number) => {
        setPage(value);
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }

    return (
        <Card sx={{ borderRadius: 2, boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)', height: '100%' }}>
            <CardContent>
                <Typography variant="h6" gutterBottom sx={{ fontWeight: 'bold', mb: 2, color: '#005a8d' }}>
                    Payment Calendar
                </Typography>
                <List>
                    {currentItems.map((item, index) => (
                        <ListItem key={index} sx={{ borderBottom: '1px solid #eee', '&:last-child': { borderBottom: 'none' } }}>
                            <ListItemIcon>
                                {item.isTransferred ? (
                                    <CheckCircleIcon color="success" />
                                ) : (
                                    <AccessTimeIcon color="action" />
                                )}
                            </ListItemIcon>
                            <ListItemText
                                primary={
                                    <Typography fontWeight="medium">
                                        {formatCurrencyFromValueAndTokenContract(item.value, tokenContract)}
                                    </Typography>
                                }
                                secondary={
                                    <Box sx={{ display: 'flex', alignItems: 'center', mt: 0.5 }}>
                                        {!item.isTransferred ? (
                                            <>
                                                <Typography variant="caption" color="textSecondary">
                                                    <strong>Transfer scheduled for</strong>: {item.willBeTransferredAt}
                                                </Typography>
                                            </>
                                        ) : (
                                            <>
                                                <Typography variant="caption" color="textSecondary">
                                                    <strong>Transferred At</strong>: {item.transferredAt}
                                                </Typography>
                                            </>
                                        )}
                                    </Box>
                                }
                            />
                            <Chip
                                label={item.isTransferred ? 'Transferred' : 'Pending'}
                                color={item.isTransferred ? 'success' : 'info'}
                                size="small"
                            />
                        </ListItem>
                    ))}
                </List>
                <Box sx={{ display: 'flex', justifyContent: 'center', mt: 2 }}>
                    <Pagination
                        count={pageCount}
                        page={page}
                        onChange={handleChange}
                        color="primary"
                        showFirstButton
                        showLastButton
                    />
                </Box>
            </CardContent>
        </Card>
    )
}