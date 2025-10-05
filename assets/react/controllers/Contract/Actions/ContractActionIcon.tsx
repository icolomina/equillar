/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Grid2, IconButton, Typography } from "@mui/material";
import { IconAction } from "../../../model/menu";

interface ContractActionsMenuProps {
    iconAction: IconAction
}

export default function ContractActionIcon({ iconAction }: ContractActionsMenuProps) {

    return (
        <Grid2 size={6} key={iconAction.id}>
            <IconButton
                onClick={iconAction.onClick}
                sx={{
                    display: 'flex',
                    flexDirection: 'column',
                    width: '100%',
                    p: 2,
                    borderRadius: 2,
                    backgroundColor: 'background.paper',
                    border: '1px solid #e0e0e0', 
                    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)', 
                    transition: 'all 0.3s ease-in-out', 
                    '&:hover': {
                        backgroundColor: 'action.hover',
                        boxShadow: '0 6px 12px rgba(0, 0, 0, 0.15)', 
                        transform: 'translateY(-2px)',
                        borderColor: 'primary.main', 
                    }
                }}
            >
                {iconAction.icon}
                <Typography variant="body2" sx={{ mt: 1, textAlign: 'center' }}>
                    {iconAction.text}
                </Typography>
            </IconButton>
        </Grid2>
    )
}