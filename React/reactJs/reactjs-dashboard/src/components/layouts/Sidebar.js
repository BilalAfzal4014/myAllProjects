import React, {Component} from 'react'
import axios from 'axios'
import {Link} from "react-router-dom";
import Helping from '../../helpers/Helping'

class Sidebar extends Component {
    constructor(props) {
        super(props)

        this.state = {
            url: URL + "admin/profile"
        }
    }

    componentWillMount() {
        if(!localStorage.token) {
            window.location.href =  "/";
        }

        axios.get(this.state.url, Helping.getConfig()).then(response => {
            // console.log(response.data)
            // $this.props.history.push("/")
        }).catch(error => {
            if(error.response.status === 403) {
                delete localStorage.token;
                window.location.href =  "/";
            }
        })
    }

    render() {
        return (
            <ul className="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <Link className="sidebar-brand d-flex align-items-center justify-content-center" to="/dashboard">
                    <div className="sidebar-brand-text mx-3">
                        <img src="../terra_virtua_logo.png" style={{width: "220px"}} alt="Terra Virtua"/>
                    </div>
                </Link>
                <hr className="sidebar-divider my-0"/>
                <li className={this.props.routeName === '/dashboard' ? 'nav-item active' : 'nav-item'}>
                    <Link className="nav-link" to="/dashboard">
                        <i className="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </Link>
                </li>
                <li className={this.props.routeName === '/users' ? 'nav-item active' : 'nav-item'}>
                    <Link className="nav-link" to="/users">
                        <i className="fas fa-users"></i>
                        <span>Users</span>
                    </Link>
                </li>
                <li className={this.props.routeName === '/news' ? 'nav-item active' : 'nav-item'}>
                    <Link className="nav-link" to="/news">
                        <i className="fas fa-fw fa-table"></i>
                        <span>News</span>
                    </Link>
                </li>
                <li className={this.props.routeName === '/categories' ? 'nav-item active' : 'nav-item'}>
                    <Link className="nav-link" to="/categories">
                        <i className="fas fa-list-alt"></i>
                        <span>Inventory Category</span>
                    </Link>
                </li>
                <li className={this.props.routeName === '/inventory' ? 'nav-item active' : 'nav-item'}>
                    <Link className="nav-link" to="/inventory">
                        <i className="fas fa-fw fa-table"></i>
                        <span>Inventory</span>
                    </Link>
                </li>
                <li className={this.props.routeName === '/feedback' ? 'nav-item active' : 'nav-item'}>
                    <Link className="nav-link" to="/feedback">
                        <i className="fas fa-fw fa-comments"></i>
                        <span>Feedback</span>
                    </Link>
                </li>
            </ul>
        )
    }
}

export default Sidebar;