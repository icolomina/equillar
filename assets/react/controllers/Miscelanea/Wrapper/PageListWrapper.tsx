/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Box, Button, Divider, Grid2, Typography } from "@mui/material";
import { ReactNode } from "react";
import { Fragment } from "react/jsx-runtime";

export interface PageListWrapperLateralButton {
    title: string,
    function: any
}

export interface PageListWrapperProps {
    title: string,
    children: ReactNode,
    sx?: Record<string, any>,
    lateralButton?: PageListWrapperLateralButton
}

export default function PageListWrapper({title, children, sx, lateralButton}: PageListWrapperProps) {

    return (
        <Fragment>
            <Box sx={{ flexGrow: 1, p: 2, ...sx }} >
                <Grid2 container spacing={2} >
                    <Grid2
                        size={12}
                        sx={{
                            display: 'flex',
                            justifyContent: 'flex-end', // Alinea el contenido a la derecha
                            alignItems: 'center', // Centra verticalmente el contenido
                        }}
                    >
                        <Typography variant="h4" sx={{ marginRight: 'auto' }}>
                            { title }
                        </Typography>
                        {
                            lateralButton?.title 
                            && <Button variant="contained" color="primary" onClick={lateralButton.function}>
                                    {lateralButton.title}
                                </Button>

                        }
                    </Grid2>
                    <Grid2 size={12}>
                        <Divider sx={{ marginY: 2 }} />
                    </Grid2>
                    <Grid2 container size={12}>
                        { children }
                    </Grid2>
                </Grid2>
            </Box>
        </Fragment>
    )
}
