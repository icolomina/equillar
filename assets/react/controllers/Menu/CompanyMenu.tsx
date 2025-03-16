import { ContentPaste } from "@mui/icons-material";
import { Divider, ListItemIcon, ListItemText, MenuItem, MenuList } from "@mui/material";
import AccountTreeIcon from '@mui/icons-material/AccountTree';
import TokenIcon from '@mui/icons-material/Token';
import { Link } from "react-router-dom";
import Home from "../HomeCompany";
import Blogs from "../Blogs";

interface CompanyMenuProps {
    handleLogout: (event: any) => void; 
}


export default function CompanyMenu(props: CompanyMenuProps) {
  return (
    <MenuList>
      <MenuItem LinkComponent={Home}>
        <ListItemIcon>
          <AccountTreeIcon fontSize="small" />
        </ListItemIcon>
        <ListItemText>
          <Link to="/app/home">Projects</Link>
        </ListItemText>
      </MenuItem>
      <MenuItem LinkComponent={Blogs}>
        <ListItemIcon>
          <TokenIcon fontSize="small" />
        </ListItemIcon>
        <ListItemText>
          <Link to="/app/blogs">Available tokens</Link>
        </ListItemText>
      </MenuItem>
      <MenuItem>
        <ListItemIcon>
          <ContentPaste fontSize="small" />
        </ListItemIcon>
        <ListItemText>Paste</ListItemText>
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
