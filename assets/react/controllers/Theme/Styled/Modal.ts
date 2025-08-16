import { styled } from '@mui/system';
import Box, { BoxProps } from '@mui/material/Box';

type OwnProps = {
  width?: number;
  border?: string;
  borderRadius?: number;
  useFlex?: boolean;
};

// 2) Unimos tipo final (BoxProps + OwnProps). Si quieres evitar por completo 
//    que BoxProps contenga un width distinto, podrías hacer Omit<BoxProps,'width'>.
type StyledModalBoxProps = BoxProps & OwnProps;

export const StyledModalBox = styled(
  Box,
  {
    shouldForwardProp: (prop: string|number|symbol) => {
      const p = String(prop);
      return !['width', 'border', 'borderRadius', 'useFlex'].includes(p);
    },
  }
)<StyledModalBoxProps>(({ theme, width = 400, border = '1px solid orange', borderRadius = 2, useFlex = false }) => ({
  position: 'absolute',
  top: '50%',
  left: '50%',
  transform: 'translate(-50%, -50%)',
  width,
  backgroundColor: theme.palette.background.paper,
  boxShadow: theme.shadows[24],
  padding: theme.spacing(4),
  border,
  borderRadius,
  ...(useFlex && {
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    alignItems: 'center',
  }),
}));