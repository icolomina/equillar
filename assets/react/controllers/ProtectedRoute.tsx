import { Navigate } from "react-router-dom";
import { useAuth } from "../hooks/AuthHook";

const ProtectedRoute = ({children}) =>  {
    const {isAuthenticathed} = useAuth();

    console.log('comprobamos que el usuario esta autenticado');
    if(!isAuthenticathed()) {
        return <Navigate to="/login" />
    }

    return children
}

export default ProtectedRoute;