// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

import { Fragment } from "react/jsx-runtime";
import { useAuth } from "../../hooks/AuthHook";
import HomeCompany from "./HomeCompany";
import HomeInvestor from "./HomeInvestor";
import HomeAdmin from "./HomeAdmin";

export default function Home() {

    const {isCompany, isAdmin} = useAuth();
    
    return (
        <Fragment>
            { 
                isAdmin() ? (
                    <HomeAdmin />
                ) : isCompany() ? (
                    <HomeCompany />
                ) : (
                    <HomeInvestor />
                )
            }
        </Fragment>
    );
}