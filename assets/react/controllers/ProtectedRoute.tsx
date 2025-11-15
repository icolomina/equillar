// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

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