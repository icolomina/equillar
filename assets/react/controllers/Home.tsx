import { Fragment } from "react/jsx-runtime";
import { useAuth } from "../hooks/AuthHook";
import HomeCompany from "./HomeCompany";
import HomeInvestor from "./HomeInvestor";

export default function Home() {

    const {isCompany, isAdmin} = useAuth();
    console.log(isCompany())
    
    return (
        <Fragment>
            {
                isCompany() || isAdmin() ? <HomeCompany /> : <HomeInvestor />
            }
        </Fragment>
    )
}