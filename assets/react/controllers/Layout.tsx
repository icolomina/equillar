import {  Box, Breadcrumbs, Divider, Grid2, ListItemIcon, ListItemText, MenuItem, MenuList, Paper, Typography, Link as MUILink, AppBar, Toolbar, Drawer } from "@mui/material";
import { Navigate, Outlet, useNavigate } from "react-router-dom";
import { useAuth } from "../hooks/AuthHook";
import CompanyMenu from "./Menu/CompanyMenu";
import InvestorMenu from "./Menu/InvestorMenu";
import Header from "./Header";
import { useContext, useEffect } from "react";
import { ReloadedRoute } from "../hooks/ReloadedRouteContext";
import { ReloadRouteContext } from "./App";

export default function Layout(props: any) {

    const {logout, isCompany} = useAuth();
    const navigate = useNavigate();
    const reloadedRoute: ReloadedRoute = useContext(ReloadRouteContext);

    const handleLogout = (event: any) => {
      event.preventDefault();
      logout();
      navigate('/login');
    }

    /*return (
        <Box sx={{ ml: { xs: 2, sm: 4 }, mr: { xs: 2, sm: 4 } }}>
            <Grid2 container spacing={2}>
                <Grid2 size={12}>
                    <Box component="section" sx={{ p: 2, mt: 2, display: 'flex', justifyContent: 'flex-start' }}>
                        <Header />
                    </Box>
                </Grid2>
            </Grid2>
            <Grid2 container spacing={2}>
                <Grid2 size={{ xs: 12, sm: 2 }}>
                    <Box component="section" sx={{ p: 2, mt: 2, display: 'flex', justifyContent: 'flex-start' }}>
                        <Paper sx={{ width: '100%' }}>
                            {
                                isCompany() 
                                    ? <CompanyMenu handleLogout={handleLogout} />
                                    : <InvestorMenu handleLogout={handleLogout} />
                            }
                        </Paper>
                    </Box>
                </Grid2>
                <Grid2 size={{ xs: 12, sm: 10 }}>
                    <Box component="section" sx={{ p: 2, mt: 2, display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100%'  }}>
                        <Paper sx={{ width: '100%' }}>
                            <Outlet />
                        </Paper>
                    </Box>
                </Grid2>
            </Grid2>
        </Box>
      );*/

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
                    {isCompany() ? (
                        <CompanyMenu handleLogout={handleLogout} />
                    ) : (
                        <InvestorMenu handleLogout={handleLogout} />
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
