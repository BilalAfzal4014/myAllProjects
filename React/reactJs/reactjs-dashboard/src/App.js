import React, {Component} from 'react';
import {BrowserRouter as Router, Route, Switch} from "react-router-dom";
import './css/sb-admin-2.css'
import './css/App.css'

import Dashboard from './components/Dashboard'
import Login from './components/Login'
import UserList from './components/users/List'
import NewsList from './components/news/List'
import NewsCreate from './components/news/Create'
import CategoriesList from './components/categories/List'
import CategoriesCreate from './components/categories/Create'
import CategoriesEdit from './components/categories/Create'
import Logout from "./components/Logout";
import InventoryList from "./components/inventory/List";
import InventoryCreate from "./components/inventory/Create";
import InventoryEdit from "./components/inventory/Create";
import NoMatch from "./components/NoMatch";
import Feedback from "./components/feedback/List"

class App extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Switch>
                    <Route path="/" exact component={Login}/>
                    <Route path="/dashboard" component={Dashboard}/>
                    <Route path="/users" component={UserList}/>
                    <Route path="/news" component={NewsList}/>
                    <Route path="/create-news" component={NewsCreate}/>
                    <Route path="/edit-news/:id" component={NewsCreate}/>
                    <Route path="/categories" component={CategoriesList}/>
                    <Route path="/inventory-category/create" component={CategoriesCreate}/>
                    <Route path="/edit-category/:id" component={CategoriesEdit}/>
                    <Route path="/logout" component={Logout}/>
                    <Route path="/inventory" component={InventoryList}/>
                    <Route path="/create-inventory" component={InventoryCreate}/>
                    <Route path="/edit-inventory/:id" component={InventoryEdit}/>
                    <Route path="/feedback" component={Feedback}/>
                    <Route component={NoMatch} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
