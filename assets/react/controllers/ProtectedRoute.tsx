import { Navigate } from "react-router-dom";
import { useAuth } from "../hooks/AuthHook";

const ProtectedRoute = ({children}) =>  {
    const {isAuthenticathed} = useAuth();

    if(!isAuthenticathed()) {
        return <Navigate to="/login" />
    }

    return children
}

export default ProtectedRoute;