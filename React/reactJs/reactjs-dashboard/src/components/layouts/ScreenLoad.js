import React, {Component} from 'react'
import {List} from 'react-content-loader'
import Sidebar from "./Sidebar";
import Navbar from "./Navbar";
import Footer from "./Footer";

class ScreenLoad extends Component {
    constructor(props) {
        super(props);

        this.state = {}
    }

    render() {
        return (
            <div id="wrapper">
                <Sidebar history={this.props.history} routeName={this.state.routeName}/>
                <div id="content-wrapper" className="d-flex flex-column">
                    <div id="content">
                        <Navbar/>
                        <div className="container-fluid">
                            <div className="card shadow mb-4">
                                <div className="card-header py-3">
                                    <h6 className="m-0 font-weight-bold text-primary">{this.props.title}</h6>
                                </div>
                                <div className="card-body">
                                    <List/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Footer/>
                </div>
            </div>
        )
    }
}

export default ScreenLoad