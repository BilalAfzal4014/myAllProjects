import React from "react";
import {Switch, Route, Redirect} from "react-router-dom";
import Login from "./components/non-auth-components/login/login";
import SignUp from "./components/non-auth-components/sign-up/sign-up";
import NotFound from "./components/non-auth-components/not-found/not-found";
import Dashboard from "./components/auth-components/dashboard/dashboard";

class App extends React.Component {

    render() {
        return (
            <div>
                <h1>I am App component</h1>
                <Switch>
                    <Route exact={true} path={'/'} component={Login}/>
                    <Route exact={true} path={'/sign-up'} component={SignUp}/>
                    <Route exact={false} path={'/dashboard'} component={Dashboard}/>
                    <Route exact={true} path={'*'} component={NotFound}/>
                </Switch>
            </div>
        )
    }
}


export default App;
