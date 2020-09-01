import React, {Component} from 'react'
import Sidebar from '../layouts/Sidebar'
import Navbar from '../layouts/Navbar'
import axios from "axios"
import Footer from "../layouts/Footer"
import {Link} from 'react-router-dom'
import Helping from '../../helpers/Helping'
import Notification from "../layouts/Notification"
import ScreenLoad from "../layouts/ScreenLoad"
import Pagination from "react-js-pagination"
import ListFragment from './ListFragment'

class List extends Component {
    constructor(props) {
        super(props);

        this.state = {
            feedbacks: {},
            url: URL + 'list-feedback',
            count: '',
            routeName: '',
            error_message: '',
            error_type: '',
            isLoading: true,
            total: 0,
            activePage: 1,
            limit: 10,
            search: '',
            is_order: false,
            sorting: 'id',
            sort_type: 'DESC',
            sort_class: 'fa fa-sort-up'
        }

        this.changeStatus = this.changeStatus.bind(this)
        this.handlePageChange = this.handlePageChange.bind(this)
        this.handleSearchChange = this.handleSearchChange.bind(this)
        this.sortBy = this.sortBy.bind(this)
    }

    componentWillMount() {
        const {pathname} = this.props.location

        this.setState({
            routeName: pathname
        })

        this.getFeedbacks()
    }

    getFeedbacks() {
        let $this = this
        let search = this.state.search

        axios.get(this.state.url + "?page=" + this.state.activePage + "&limit=" + this.state.limit + "&search=" + search.trim() + "&sorting=" + this.state.sorting + "&sort_type=" + this.state.sort_type, Helping.getConfig()).then(response => {
            $this.setState({
                feedbacks: response.data.payload.feedbacks,
                count: response.data.payload.feedbacks.length,
                total: response.data.payload.total
            })

            $this.setState({isLoading: false})
        }).catch(error => {
            $this.setState({isLoading: false})
            if (error.response) {
                Helping.isAuth($this, error.response)

                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger'
                })
            }
        })
    }

    handlePageChange(pageNumber) {
        if (this.state.activePage === pageNumber) {
            return false;
        }

        this.setState({
            activePage: pageNumber,
            isLoading: true
        }, () => {
            this.getFeedbacks()
        });
    }

    changeStatus(index, object, status) {
        let $this = this

        if (window.confirm('Are you sure???')) {
            axios.post(URL + 'update-feedback/' + object.id, {status: status}, Helping.getConfig()).then(response => {
                const feedbacks = $this.state.feedbacks

                feedbacks[index].status = status
                $this.setState({
                    feedbacks: feedbacks
                })

                $this.setState({
                    error_message: 'Feedback closed successfully.',
                    error_type: 'success'
                })
                document.getElementById('nprogress').style.display = 'none';
            }).catch(error => {
                document.getElementById('nprogress').style.display = 'none';
                if (error.response) {
                    $this.setState({
                        error_message: error.response.data.message,
                        error_type: 'danger'
                    })
                }
            })
        }
    }

    handleSearchChange(e) {
        let $this = this

        this.setState({
            search: e.target.value,
            activePage: 1
        }, () => {
            if ($this.state.search.length > 2) {
                $this.getFeedbacks()
            }

            if ($this.state.search.length === 0) {
                $this.getFeedbacks()
            }
        })
    }

    sortBy(key) {
        let $this = this
        let sortType = this.state.sort_type
        let sortClass = this.state.sort_class

        if (this.state.sort_type === 'ASC') {
            sortType = 'DESC'
            sortClass = "fa fa-sort-up"
        }

        if (this.state.sort_type === 'DESC') {
            sortType = 'ASC'
            sortClass = "fa fa-sort-down"
        }

        this.setState({
            sorting: key,
            activePage: 1,
            is_order: true,
            sort_type: sortType,
            sort_class: sortClass,
        }, () => {
            $this.getFeedbacks()
        })
    }

    render() {
        if (this.state.isLoading) {
            return (
                <ScreenLoad title="App Feedback"/>
            )
        }

        return (
            <div id="wrapper">
                <Sidebar history={this.props.history} routeName={this.state.routeName}/>
                <div id="content-wrapper" className="d-flex flex-column">
                    <div id="content">
                        <Navbar/>
                        <div className="container-fluid">
                            {this.state.error_message &&
                            <Notification type={this.state.error_type} message={this.state.error_message}/>
                            }
                            <div className="card shadow mb-4">
                                <div className="card-header py-3">
                                    <h6 className="m-0 font-weight-bold text-primary">App Feedback
                                        <input type="text" placeholder="Search" onChange={this.handleSearchChange}
                                               value={this.state.search} className="form-control"
                                               style={{"width": "30%", "float": "right"}}/>
                                    </h6>
                                </div>
                                <div className="card-body">
                                    {this.state.total > 0 ? (
                                        <div className="table-responsive">
                                            <ListFragment count={this.state.count} feedbacks={this.state.feedbacks}
                                                          object={this} is_order={this.state.is_order}
                                                          sorting={this.state.sorting}
                                                          sort_class={this.state.sort_class}/>
                                            <div className="pagination-alignment">
                                                <Pagination
                                                    hideNavigation
                                                    activePage={this.state.activePage}
                                                    itemsCountPerPage={10}
                                                    totalItemsCount={this.state.total}
                                                    pageRangeDisplayed={5}
                                                    onChange={this.handlePageChange}
                                                    itemClass="page-item"
                                                    linkClass="page-link"
                                                />
                                            </div>
                                        </div>
                                    ) : (
                                        <div className="table-responsive">
                                            <Notification type="danger" message="App Feedback not found."/>
                                        </div>
                                    )}
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

export default List