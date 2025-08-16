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