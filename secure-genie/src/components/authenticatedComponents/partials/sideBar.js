import React from "react";
import {Link, withRouter} from "react-router-dom";

class SideBar extends React.Component {
    constructor(props) {
        super(props);
        //console.log();
        //this.props.history.push("/dashboard");
    }

    render() {
        return (
            <aside id="sidebar">
                <div className="sidebar_holder">
                    <ul className="list_none">
                        <li className={this.props.location.pathname == '/dashboard/user-management' ? 'active' : ''}>
                            <Link to="/dashboard/user-management">
                                <img src="/images/ico1.png" alt="#"/>
                                <span>User Management</span>
                            </Link>
                        </li>
                        <li className={this.props.location.pathname == '/dashboard/kyc' ? 'active' : ''}>
                            <Link to="/dashboard/kyc"><img src="/images/ico2.png" alt="#"/>
                                <span>KYC</span></Link>
                        </li>

                        <li className={this.props.location.pathname == '/dashboard/department-management' ? 'active' : ''}>
                            <Link to="/dashboard/department-management"><img src="/images/ico11.png" alt="#"/>
                                <span>DEPARTMENTS <br/> MANAGEMENT</span></Link></li>
                        <li className={this.props.location.pathname == '/dashboard/regulator-management' ? 'active' : ''}>
                            <Link to="/dashboard/regulator-management"><img src="/images/ico12.png" alt="#"/>
                                <span>REGULATORS <br/> MANAGEMENT</span></Link></li>

                        <li className={this.props.location.pathname == '/dashboard/department-listing' ? 'active' : ''}>
                            <Link to="/dashboard/department-listing"><img src="/images/ico3.png" alt="#"/>
                                <span>Documents</span></Link>
                        </li>
                        <li className={this.props.location.pathname == '/dashboard/settings' ? 'active' : ''}>
                            <Link to="/dashboard/settings"><img src="/images/ico4.png" alt="#"/>
                                <span>Settings</span></Link></li>
                    </ul>
                    <a href="#" className="logout_btn"><img src="/images/ico6.png" alt="#"/> <span>SIGN OUT</span></a>
                </div>
            </aside>
        )
    }
}

export default withRouter(SideBar);
