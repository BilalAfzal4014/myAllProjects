import React from 'react';
import {Switch, Route, Redirect} from "react-router-dom";
import Login from "../src/Components/UnauthenticatedComponents/login";
import SignUp from "../src/Components/UnauthenticatedComponents/signUp";
import Dashboard from "../src/Components/AuthenticatedComponents/dashboard";
import {connect} from "react-redux";
import {intializeFireBase} from "./sdk/fireBaseIntegration/makeFirebaseConnection";

class App extends React.Component {

    componentDidMount() {
        if (this.props.token != null) {
            intializeFireBase((token) => {
            });
        }
    }

    render() {
        return (
            <div>
                <Switch>
                    <Route exact path="/" render={() => {
                        return this.props.token == null ? <Redirect to="/sign-up"/> : <Redirect to="/dashboard"/>
                    }}/>
                    <Route exact path="/login" render={() => {
                        return this.props.token == null ? <Login/> : <Redirect to="/dashboard"/>
                    }}/>
                    <Route exact path="/sign-up" render={() => {
                        return this.props.token == null ? <SignUp/> : <Redirect to="/dashboard"/>
                    }}/>
                    <Route exact path="/dashboard" render={() => {
                        return this.props.token != null ? <Dashboard/> : <Redirect to="/"/>
                    }}/>
                    <Route path="*" render={() => {
                        return (
                            <h1 style={{"text-align": "center"}}>Path doesn't exist</h1>
                        )
                    }}/>
                </Switch>
            </div>
        );
    }

}

const mapStateToProps = (state) => {
    return {
        token: state.userLoginToken
    };
};

const mapDispatchToProps = (dispatch) => {
    return {};
};

export default connect(mapStateToProps, mapDispatchToProps)(App);
