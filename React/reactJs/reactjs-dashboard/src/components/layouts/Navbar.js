import React, {Component} from 'react'
import {Link} from "react-router-dom";

class Navbar extends Component {
    constructor(props) {
        super(props)

        this.state = {}
    }

    render() {
        return (
            <nav className="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" className="btn btn-link d-md-none rounded-circle mr-3">
                    <i className="fa fa-bars"></i>
                </button>
                <ul className="navbar-nav ml-auto">
                    <li className="nav-item">
                        <Link className="nav-link" to="/logout"><i className="fas fa-sign-out-alt"></i>&nbsp; Logout</Link>
                    </li>
                </ul>
            </nav>
        )
    }
}

export default Navbar;