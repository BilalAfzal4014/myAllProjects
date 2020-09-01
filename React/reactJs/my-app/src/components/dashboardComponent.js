import React, {Component} from 'react';
import {BrowserRouter as Router, Switch, Route, Link, Redirect, withRouter, NavLink} from 'react-router-dom';
import Child1 from './dashboardChildComponents/child1';
import Child2 from './dashboardChildComponents/child2';
import Child3 from './dashboardChildComponents/child3';

class Dashboard extends Component {
    constructor(props) {
        super(props);
        //this.props.history.listen((location, action) => {
        //console.log(this.props, location, action);
        //});
    }

    render() {
        return (
            <div>
                <h1>Dashboard top</h1>
                <ul>
                    <li><Link to='/dashboard/child1'>child1</Link></li>
                    <li><Link to='/dashboard/child2'>child2</Link></li>
                    <li><Link to='/dashboard/child3/1'>child3-1</Link></li>
                    <li><Link to='/dashboard/child3/2'>child3-2</Link></li>
                    <li><Link to='/dashboard'>Dashboard</Link></li>
                </ul>

                {/*<Switch>*/}
                <Route exact path='/dashboard/child1' component={Child1}/>
                <Route exact path='/dashboard/child2' component={Child2}/>
                <Route exact path='/dashboard/child3/:id' render={({match, ...rest}) => {
                    return routePermission(match, rest, match.params.id, Child3);
                }}/>
                {/*</Switch>*/}

                <h1>Dashboard bottom</h1>
            </div>
        );
    }
}

const routePermission = (match, rest, key, Component) => {
    // we can also write this function in separate file and then import it to required component
    console.log(match, rest, key);
    if (key == 2) {
        return (<Redirect to='/dashboard'/>);
    }
    return (<Component match={match}/>);
    //we can also redirect with match.history.push('your-path') to desired component
};

//export default Dashboard;
export default withRouter(Dashboard);
