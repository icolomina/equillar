/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { Box, Divider, List, ListItem, ListItemButton, ListItemIcon, ListItemText, Typography } from "@mui/material";
import { useLocation, useNavigate } from 'react-router-dom';
import LogoutIcon from '@mui/icons-material/Logout'

interface MenuProps {
    handleLogout: (event: any) => void; 
    items: any;
}


export default function Menu(props: MenuProps) {

  const navigate = useNavigate();
  const location = useLocation();

  const renderSection = (sectionName, sectionItems) => (
        <Box sx={{ mb: 2 }}>
            <Typography variant="subtitle2" sx={{ fontWeight: 600, mb: 1, color: 'primary.main' }}>
                {sectionName}
            </Typography>
            <List dense>
                {sectionItems.map(item => (
                    <ListItem key={item.path} disablePadding>
                        <ListItemButton
                            selected={location.pathname === item.path}
                            onClick={() => navigate(item.path)}
                            sx={{ borderRadius: 1 }}
                        >
                            <ListItemIcon sx={{ minWidth: 36 }}>
                                {item.icon}
                            </ListItemIcon>
                            <ListItemText primary={item.label} />
                        </ListItemButton>
                    </ListItem>
                ))}
            </List>
            <Divider sx={{ mt: 1 }} />
        </Box>
    );

    return (
        <Box>
            {props.items.general && renderSection("GENERAL", props.items.general)}
            {props.items.operations && renderSection("OPERATIONS", props.items.operations)}
            <Box sx={{ mt: 2 }}>
                <List dense>
                    <ListItem disablePadding>
                        <ListItemButton
                            onClick={props.handleLogout}
                            sx={{
                                borderRadius: 1,
                                bgcolor: 'primary.light',
                                color: 'primary.contrastText',
                                '&:hover': {
                                    bgcolor: 'primary.main',
                                    color: 'white'
                                },
                                fontWeight: 600
                            }}
                        >
                            <ListItemIcon sx={{ minWidth: 36, color: 'inherit' }}>
                                <LogoutIcon />
                            </ListItemIcon>
                            <ListItemText primary="Logout" sx={{ fontWeight: 700 }} />
                        </ListItemButton>
                    </ListItem>
                </List>
            </Box>
        </Box>
    );
}
