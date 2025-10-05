/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
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