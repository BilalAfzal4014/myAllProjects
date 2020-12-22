import React, {Component} from 'react';
import {BrowserRouter as Router, Switch, Route, Link, Redirect, withRouter, NavLink} from 'react-router-dom';
import './App.css';

import Main from './components/mainComponent';
import Dashboard from './components/dashboardComponent';
import Root from './components/rootComponent';
import RegisterAndUnregisterUserHolder from "./components/registerAndUnregisterUserHolderComponent";
import ButtonHolder from "./components/ButtonComponentsAndTheirHolder/buttonHolder";
import {connect} from "react-redux";
import {setTokenAction, removeTokenAction} from "./store/actions/tokenAction";

class App extends Component {
    constructor(props) {
        super(props);
        /*this.state = {
            isLoggedIn: false
        };*/

        //console.log(global.text);

        this.loginFunction = this.loginFunction.bind(this);
    }

    loginFunction() {
        /*this.setState((state) => ({
            isLoggedIn: !state.isLoggedIn
        }));*/

        //the above and both syntax are same

        /*this.setState((state) => {
            return {
                isLoggedIn: !state.isLoggedIn
            }
        })*/

        if (this.props.token == null) {
            this.props.setToken("123abcxyz");
            this.props.history.push("/dashboard");
        } else {
            this.props.removeToken();
            this.props.history.push("/");
        }
    }

    render() {
        return (
            <div>
                <h1>app component top</h1>
                <ul>
                    <li><Link to='/'>main</Link></li>
                    <li><Link to='/register-unregister'>Register-unRegister</Link></li>
                    <li><Link to='/dashboard'>dashboard</Link></li>
                    <li><Link to='/root/1'>root-1</Link></li>
                    <li><Link to='/root/2'>root-2</Link></li>
                    <li><Link to='/hoc-example'>HOC example</Link></li>
                </ul>
                <Switch>
                    <Route exact path='/' component={Main}/>
                    <Route exact path='/register-unregister' component={RegisterAndUnregisterUserHolder}/>
                    <Route path='/dashboard' render={() => {
                        return this.props.token != null ? <Dashboard/> : <Redirect to='/'/>;
                    }}/>
                    <Route exact path='/root/:justForTesting' render={({match}) => {
                        return this.props.token != null ? <Redirect to='/dashboard'/> : <Root match={match}/>;
                    }}/>
                    <Route exact path='/hoc-example' component={ButtonHolder}/>
                </Switch>
                <h1>app component bottom</h1>
                <button onClick={this.loginFunction}>{this.props.token != null ? 'logout' : 'login'}</button>
            </div>
        );
    }
}


const matchStateToProps = (state) => {
    return {
        token: state.tokenReducer
    }
};

const matchDispatchToProps = (dispatch) => {
    return {
        setToken: (token) => {
            dispatch(setTokenAction(token));
        },
        removeToken: () => {
            dispatch(removeTokenAction());
        }
    }
};

export default connect(matchStateToProps, matchDispatchToProps)(withRouter(App));

//withRouter needs to bind with those component that are using store or those components which have <Route/> component


/*
// below is the code if you want to get redux store data in javascript function
import store from './redux/store.js';

function aFunction(){
   let data =store.getState().yourReducerName;
   console.log(data);
}

store.subscribe(aFunction)*/