/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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