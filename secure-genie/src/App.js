import React from 'react';
import {Switch, Route} from "react-router-dom";
import Login from "./components/unauthenticatedComponents/login";
import Dashboard from "./components/authenticatedComponents/dashboard";

class App extends React.Component {
    constructor(props) {
        super(props);


    }


    render() {
        return (
            <div>
                <Switch>
                    <Route exact path="/login" component={Login}/>
                    <Route path="/dashboard" component={Dashboard}/>
                </Switch>
            </div>
        )
    }

}

export default App;
