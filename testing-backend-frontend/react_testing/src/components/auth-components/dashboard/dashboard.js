import React from "react";
import {Route, Switch, Link} from "react-router-dom";
import Category from "../category/category";
import Product from "../product/product";
import Course from "../course/course";
import NotFound from "../../non-auth-components/not-found/not-found";

class Dashboard extends React.Component {

    render() {
        return (
            <div>
                <h1>I am Dashboard component start</h1>
                <Link to={'/dashboard/categories'}>Categories</Link>
                <Link to={'/dashboard/products'}>Products</Link>
                <Link to={'/dashboard/courses'}>Courses</Link>
                <Switch>
                    <Route exact={true} path={'/dashboard/categories'} component={Category}/>
                    <Route exact={true} path={'/dashboard/products'} component={Product}/>
                    <Route exact={true} path={'/dashboard/courses'} component={Course}/>
{/*
                    <Route exact={true} path={'*'} component={NotFound}/>
*/}
                </Switch>
                <h1>I am Dashboard component end</h1>
            </div>
        )
    }
}

export default Dashboard;