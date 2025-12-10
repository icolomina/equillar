// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Wallet } from "@mui/icons-material"
import { Box, Divider, Grid2, Typography } from "@mui/material"
import { Fragment } from "react"

export interface UserPortfolioResumeDataProps {
    title: string,
    resumeInfo: Record<string, string>
}

const tokenInfoStyle = {
    marginBottom: '8px',
};

const resumeInfoStyle = {
    borderRight: '1px solid #e0e0e0', 
    paddingRight: 2, 
    paddingLeft: 2 
}

const resumeInfoDividerStyle = {
    my: 1, 
    borderBottomWidth: 2,
    borderColor: 'primary.main'
}

export default function UserPortfolioResumeData({ title, resumeInfo }: UserPortfolioResumeDataProps){

    return (
        <Fragment>
            <Grid2 size={{ xs: 12, sm: 6, md: 2 }} sx={resumeInfoStyle}>
                <Box sx={{ display: 'flex', alignItems: 'center', marginBottom: 1 }}>
                    <Wallet sx={{ marginRight: 1 }} />
                    <Typography variant="subtitle1" sx={{ fontWeight: 'bold' }}>{title}</Typography>
                </Box>
                <Divider sx={resumeInfoDividerStyle} /> {/* Divider estilizado */}
                {Object.entries(resumeInfo).map(([token, amount]) => (
                    <Grid2 container spacing={1} key={token} sx={tokenInfoStyle}>
                        <Grid2 size={6}>
                            <Typography variant="subtitle2">{token}:</Typography>
                        </Grid2>
                        <Grid2 size={6}>
                            <Typography variant="body2">{amount}</Typography>
                        </Grid2>
                    </Grid2>
                ))}
            </Grid2>
        </Fragment>
    )
}