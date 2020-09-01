import React, {Component} from 'react'
import Sidebar from './layouts/Sidebar'
import Navbar from './layouts/Navbar'
import axios from "axios"
import Helping from "../helpers/Helping"
import Footer from "./layouts/Footer"
import UserStats from './layouts/UserStats'

class Dashboard extends Component {
    constructor(props) {
        super(props)

        this.state = {
            routeName: '',
            url: URL,
            users: 0,
            news: 0,
            categories: 0,
            inventories: 0,
            users_type: 'weekly',
            options: {}
        }

        this.handleChangeUsersType = this.handleChangeUsersType.bind(this)
    }

    componentWillMount() {
        const {pathname} = this.props.location

        this.setState({
            routeName: pathname
        })

        this.getUsers()
        this.getNews()
        this.getCategories()
        this.getInventories()

        this.getUserStats()
    }

    getUsers() {
        var $this = this

        axios.get(this.state.url + 'user-list?sort_type=DESC&sorting=id', Helping.getConfig()).then(response => {
            $this.setState({
                users: response.data.payload.total
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    getNews() {
        let $this = this

        axios.get(this.state.url + "news", Helping.getConfig()).then(response => {
            $this.setState({
                news: response.data.payload.total
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    getCategories() {
        let $this = this

        axios.get(this.state.url + "category-list?sort_type=DESC&sorting=id", Helping.getConfig()).then(response => {
            $this.setState({
                categories: response.data.payload.total
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    getInventories() {
        let $this = this

        axios.get(this.state.url + 'inventory-list?sort_type=DESC&sorting=id', Helping.getConfig()).then(response => {
            $this.setState({
                inventories: response.data.payload.total
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    handleChangeUsersType(e) {
        let $this = this

        this.setState({
            users_type: e.target.value
        }, function () {
            $this.getUserStats()
        })
    }

    getUserStats() {
        let $this = this

        axios.get(this.state.url + "user-stats?type=" + this.state.users_type, Helping.getConfig()).then(response => {
            const options = {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Users Stats'
                },
                xAxis: {
                    categories: response.data.payload.columns
                },
                series: [
                    {showInLegend: false, data: response.data.payload.users, name: 'Series'}
                ]
            }

            $this.setState({
                options: options
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    render() {
        return (
            <div id="wrapper">
                <Sidebar history={this.props.history} routeName={this.state.routeName}/>
                <div id="content-wrapper" className="d-flex flex-column">
                    <div id="content">
                        <Navbar/>
                        <div className="container-fluid">
                            <div className="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 className="h3 mb-0 text-gray-800">Dashboard</h1>
                            </div>
                            <div className="row">
                                <div className="col-xl-3 col-md-6 mb-4">
                                    <div className="card border-left-primary shadow h-100 py-2">
                                        <div className="card-body">
                                            <div className="row no-gutters align-items-center">
                                                <div className="col mr-2">
                                                    <div
                                                        className="text-xs font-weight-bold text-primary text-uppercase mb-1">Users
                                                    </div>
                                                    <div
                                                        className="h5 mb-0 font-weight-bold text-gray-800">{this.state.users}
                                                    </div>
                                                </div>
                                                <div className="col-auto">
                                                    <i className="fas fa-users fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-xl-3 col-md-6 mb-4">
                                    <div className="card border-left-success shadow h-100 py-2">
                                        <div className="card-body">
                                            <div className="row no-gutters align-items-center">
                                                <div className="col mr-2">
                                                    <div
                                                        className="text-xs font-weight-bold text-success text-uppercase mb-1">News
                                                    </div>
                                                    <div
                                                        className="h5 mb-0 font-weight-bold text-gray-800">{this.state.news}
                                                    </div>
                                                </div>
                                                <div className="col-auto">
                                                    <i className="fas fa-fw fa-table fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-xl-3 col-md-6 mb-4">
                                    <div className="card border-left-info shadow h-100 py-2">
                                        <div className="card-body">
                                            <div className="row no-gutters align-items-center">
                                                <div className="col mr-2">
                                                    <div
                                                        className="text-xs font-weight-bold text-info text-uppercase mb-1">Inventory
                                                        Category
                                                    </div>
                                                    <div className="row no-gutters align-items-center">
                                                        <div className="col-auto">
                                                            <div
                                                                className="h5 mb-0 mr-3 font-weight-bold text-gray-800">{this.state.categories}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="col-auto">
                                                    <i className="fas fa-list-alt fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-xl-3 col-md-6 mb-4">
                                    <div className="card border-left-warning shadow h-100 py-2">
                                        <div className="card-body">
                                            <div className="row no-gutters align-items-center">
                                                <div className="col mr-2">
                                                    <div
                                                        className="text-xs font-weight-bold text-warning text-uppercase mb-1">Inventory
                                                    </div>
                                                    <div
                                                        className="h5 mb-0 font-weight-bold text-gray-800">{this.state.inventories}</div>
                                                </div>
                                                <div className="col-auto">
                                                    <i className="fas fa-fw fa-table fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-xl-12 col-md-12 mb-4">
                                    <div className="card shadow h-100 py-2">
                                        <select className="form-control"
                                                style={{"width": "20%", "marginLeft": "79%"}}
                                                value={this.state.users_type} onChange={this.handleChangeUsersType}>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                        <div className="card-body">
                                            <UserStats options={this.state.options}/>
                                        </div>
                                    </div>
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

export default Dashboard;