/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Box, Button, Typography } from "@mui/material";
import { useNavigate } from "react-router-dom";
import { Fragment } from "react/jsx-runtime";

export default function WithdrawalRequestConfirmed() {

    const navigate = useNavigate();

    const goToWithdrawalRequestsList = () => {
        return navigate('/app/get-withdrawal-requests');
    }

    return (
        <Fragment>
            <Box sx={{ p: 2, mt: 2, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', height: '100%' }} >
                <Typography variant="h6" sx={{ mb: 2 }}>
                    Your withdrawal request has been confirmed. You will receive the funds after the team approve it
                </Typography>
                <Button variant="contained" color="primary" onClick={() => goToWithdrawalRequestsList()}>
                    Check my withdrawal requests
                </Button>
            </Box>
        </Fragment>
    );
}