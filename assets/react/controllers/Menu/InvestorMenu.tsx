import { Divider, ListItemIcon, ListItemText, MenuItem, MenuList } from "@mui/material";
import AccountTreeIcon from '@mui/icons-material/AccountTree';
import Home from "../HomeCompany";
import { Link } from "react-router-dom";
import { ContentPaste } from "@mui/icons-material";

interface InvestorMenuProps {
    handleLogout: (event: any) => void; 
}

export default function InvestorMenu(props: InvestorMenuProps) {

    return (
      <MenuList>
        <MenuItem LinkComponent={Home}>
          <ListItemIcon>
            <AccountTreeIcon fontSize="small" />
          </ListItemIcon>
          <ListItemText>
            <Link to="/app/home-investor">Avalable projects</Link>
          </ListItemText>
        </MenuItem>
        <Divider />
        <MenuItem>
          <ListItemIcon>
            <ContentPaste fontSize="small" />
          </ListItemIcon>
          <ListItemText>
            <Link to="#" onClick={props.handleLogout}>
              Logout
            </Link>
          </ListItemText>
        </MenuItem>
      </MenuList>
    );
}