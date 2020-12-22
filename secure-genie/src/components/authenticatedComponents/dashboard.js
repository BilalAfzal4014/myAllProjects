import React from 'react';
import {Switch, Route} from "react-router-dom";
import Header from "../authenticatedComponents/partials/header";
import SideBar from "../authenticatedComponents/partials/sideBar";
import UserManagement from "../authenticatedComponents/dashboardChildComponents/userManagement/userManagement";
import Kyc from "../authenticatedComponents/dashboardChildComponents/kyc/kyc";
import DepartmentManagement
    from "../authenticatedComponents/dashboardChildComponents/departmentManagement/departmentManagement";
import RegulatorManagement
    from "../authenticatedComponents/dashboardChildComponents/regulatorManagement/regulatorManagement";
import DepartmentListing from "../authenticatedComponents/dashboardChildComponents/departmentListing/departmentListing";
import Settings from "../authenticatedComponents/dashboardChildComponents/settings/settings";

class Dashboard extends React.Component {
    constructor(props) {
        super(props);


    }


    render() {
        return (
            <div>
                <Header/>
                <main id="main">
                    <SideBar/>
                    <div id="content">
                        <Route exact path="/dashboard/user-management" component={UserManagement}/>
                        <Route exact path="/dashboard/kyc" component={Kyc}/>
                        <Route exact path="/dashboard/department-management" component={DepartmentManagement}/>
                        <Route exact path="/dashboard/regulator-management" component={RegulatorManagement}/>
                        <Route exact path="/dashboard/department-listing" component={DepartmentListing}/>
                        <Route exact path="/dashboard/settings" component={Settings}/>
                    </div>
                </main>
            </div>
        )
    }

}

export default Dashboard;