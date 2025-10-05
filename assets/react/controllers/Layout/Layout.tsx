/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Box, Grid2, Paper } from "@mui/material";
import { Outlet, useNavigate } from "react-router-dom";
import { useAuth } from "../../hooks/AuthHook";
import Menu from "./Menu/Menu";
import Header from "./Header";
import { companyMenuItems, inversorMenuItems } from "./Menu/MenuItems";

export default function Layout(props: any) {

    const { logout, isCompany, isAdmin } = useAuth();
    const navigate = useNavigate();

    const handleLogout = (event: any) => {
        event.preventDefault();
        logout();
        navigate('/login');
    }

    return (
        <Box sx={{ ml: { xs: 2, sm: 4 }, mr: { xs: 2, sm: 4 } }}>
            <Grid2 container spacing={2}>
                <Grid2 size={12}>
                    <Box component="section" sx={{ mt: 2 }}>
                        <Header />
                    </Box>
                </Grid2>
            </Grid2>
            <Grid2 container spacing={2}>
                <Grid2 size={{ xs: 12, sm: 2 }}>
                    <Box component="section" sx={{ mt: 2 }}>
                        <Paper sx={{ width: '100%', p: 2 }}>
                            {(isCompany() || isAdmin()) ? (
                                <Menu handleLogout={handleLogout} items={companyMenuItems} />
                            ) : (
                                <Menu handleLogout={handleLogout} items={inversorMenuItems} />
                            )}
                        </Paper>
                    </Box>
                </Grid2>
                <Grid2 size={{ xs: 12, sm: 10 }}>
                    <Box component="section" sx={{ mt: 2 }}>
                        <Paper sx={{ width: '100%', p: 3, minHeight: '400px' }}>
                            <Outlet />
                        </Paper>
                    </Box>
                </Grid2>
            </Grid2>
        </Box>
    );
}
