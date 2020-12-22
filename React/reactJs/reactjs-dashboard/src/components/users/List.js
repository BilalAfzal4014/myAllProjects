import React, {Component} from 'react'
import Sidebar from '../layouts/Sidebar'
import Navbar from '../layouts/Navbar'
import axios from "axios";
import Footer from "../layouts/Footer";
import Helping from '../../helpers/Helping'
import ScreenLoad from '../layouts/ScreenLoad'
import Notification from "../layouts/Notification";
import Pagination from "react-js-pagination";

class List extends Component {
    constructor(props) {
        super(props);

        this.state = {
            users: {},
            url: URL + 'user-list',
            count: '',
            routeName: '',
            userRow: '',
            index: 0,
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
            sort_class: 'fa fa-sort-up',
        }

        this.confirmedEvent = this.confirmedEvent.bind(this)
        this.enabledEvent = this.enabledEvent.bind(this)
        this.handlePageChange = this.handlePageChange.bind(this)
        this.handleSearchChange = this.handleSearchChange.bind(this)
        this.searchEvent = this.searchEvent.bind(this)
        this.resetFilter = this.resetFilter.bind(this)
        this.sortBy = this.sortBy.bind(this)
    }

    componentWillMount() {
        const {pathname} = this.props.location

        this.setState({
            routeName: pathname
        })

        this.getUsers()
    }

    handlePageChange(pageNumber) {
        if (this.state.activePage === pageNumber) {
            return false;
        }

        this.setState({
            activePage: pageNumber,
            isLoading: true
        }, () => {
            this.getUsers()
        });
    }

    getUsers() {
        var $this = this
        let search = this.state.search

        axios.get(this.state.url + '?page=' + this.state.activePage + '&limit=' + this.state.limit + '&search=' + search.trim() + "&sorting=" + this.state.sorting + "&sort_type=" + this.state.sort_type, Helping.getConfig()).then(response => {
            $this.setState({
                users: response.data.payload.users,
                count: response.data.payload.users.length,
                total: response.data.payload.total
            })
            $this.setState({isLoading: false})
        }).catch(error => {
            $this.setState({isLoading: false})
            console.log(error.response)
            if (error.response) {
                Helping.isAuth($this, error.response)

                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger'
                })
            }
        })
    }

    isEnabled(enable) {
        if (enable) {
            return (
                <label style={{color: "green"}}>Enabled</label>
            )
        }

        return (
            <label style={{color: "red"}}>Disabled</label>
        )
    }

    isConfirmed(confirmed) {
        if (confirmed) {
            return (
                <label style={{color: "green"}}>Yes</label>
            )
        }

        return (
            <label style={{color: "red"}}>No</label>
        )
    }

    edit(e, user, i) {
        this.setState({
            userRow: user,
            index: i
        })
    }

    enabledEvent(e, status) {
        let $this = this
        let user = this.state.userRow

        let enabled = user.isEnable ? 1 : 0

        if (status === enabled) {
            return
        }

        status = status === 1 ? true : false

        axios.put(URL + 'admin/user/' + user.id, {isEnable: status}, Helping.getConfig()).then(response => {
            var users = $this.state.users
            var index = $this.state.index

            users[index].isEnable = status
            $this.setState({
                users: users
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    confirmedEvent(e, status) {
        let $this = this
        let user = this.state.userRow
        let confirmed = user.isConfirmed ? 1 : 0

        if (status === confirmed) {
            return
        }

        var users = this.state.users
        var index = this.state.index

        status = status === 1 ? true : false

        axios.put(URL + 'admin/user/' + user.id, {isConfirmed: status}, Helping.getConfig()).then(response => {
            users[index].isConfirmed = status
            $this.setState({
                users: users
            })
        }).catch(error => {
            console.log(error.response)
        })
    }

    handleSearchChange(e) {
        let $this = this

        this.setState({
            search: e.target.value,
            activePage: 1
        }, () => {
            if ($this.state.search.length > 3) {
                $this.getUsers()
            }

            if ($this.state.search.length === 0) {
                $this.getUsers()
            }
        })
    }

    searchEvent() {
        this.setState({isLoading: true})
        this.getUsers()
    }

    resetFilter() {
        let $this = this

        if (this.state.search) {
            $this.setState({isLoading: true})

            $this.setState({
                search: ''
            }, () => {
                $this.getUsers()
            });
        }
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
            $this.getUsers()
        })
    }

    render() {
        if (this.state.isLoading) {
            return (
                <ScreenLoad title="Users"/>
            )
        }

        return (
            <div>
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
                                        <h6 className="m-0 font-weight-bold text-primary">Users
                                            <input type="text" placeholder="Search" onBlur={this.handleSearchChange}
                                                   onChange={this.handleSearchChange}
                                                   value={this.state.search} className="form-control"
                                                   style={{"width": "30%", "float": "right"}}/>
                                        </h6>
                                    </div>
                                    <div className="card-body">
                                        {this.state.total > 0 ? (
                                            <div className="table-responsive">
                                                <table className="table table-bordered" id="dataTable" width="100%"
                                                       cellSpacing="0" style={{"tableLayout": "fixed"}}>
                                                    <thead>
                                                    <tr>
                                                        <th style={{"cursor": "pointer", "width": "6%"}}
                                                            onClick={() => this.sortBy('id')}>ID
                                                            {this.state.is_order && this.state.sorting === 'id' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}}
                                                            onClick={() => this.sortBy('first_name')}>First Name
                                                            {this.state.is_order && this.state.sorting === 'first_name' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}}
                                                            onClick={() => this.sortBy('last_name')}>Last Name
                                                            {this.state.is_order && this.state.sorting === 'last_name' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}}
                                                            onClick={() => this.sortBy('username')}>Username
                                                            {this.state.is_order && this.state.sorting === 'username' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}}
                                                            onClick={() => this.sortBy('email')}>Email
                                                            {this.state.is_order && this.state.sorting === 'email' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer", "width": "10%"}}
                                                            onClick={() => this.sortBy('is_enable')}>Status
                                                            {this.state.is_order && this.state.sorting === 'is_enable' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer", "width": "11%"}}
                                                            onClick={() => this.sortBy('is_confirmed')}>Confirmed
                                                            {this.state.is_order && this.state.sorting === 'is_confirmed' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"width": "8%"}}>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this.state.count &&
                                                    this.state.users.map((user, i) => (
                                                        <tr key={user.id}>
                                                            <td>{user.id}</td>
                                                            <td>{user.firstName}</td>
                                                            <td>{user.lastName}</td>
                                                            <td>{user.username}</td>
                                                            <td>{user.email}</td>
                                                            <td>
                                                                {this.isEnabled(user.isEnable)}
                                                            </td>
                                                            <td>
                                                                {this.isConfirmed(user.isConfirmed)}
                                                            </td>
                                                            <td>
                                                                <button type="button" className="btn btn-info"
                                                                        data-toggle="modal" data-target="#userModal"
                                                                        onClick={(e) => this.edit(e, user, i)}>
                                                                    <i className="fas fa-pencil-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    ))
                                                    }
                                                    </tbody>
                                                </table>
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
                                                <Notification type="danger" message="Users not found."/>
                                            </div>
                                        )}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <Footer/>
                    </div>
                </div>
                <div className="modal" id="userModal">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h4 className="modal-title">Edit User</h4>
                                <button type="button" className="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div className="modal-body">
                                <div className="row">
                                    <div className="col-4">
                                        {this.state.userRow.avatarUrl ? (
                                            <img src={this.state.userRow.avatarUrl}
                                                 alt="User profile"
                                                 className="avatar-image rounded-circle img-thumbnail"
                                                 style={{width: "100px", height: "100px"}}/>
                                        ) : (
                                            <img src="/img/default_user_img.png"
                                                 alt="User profile"
                                                 className="avatar-image rounded-circle img-thumbnail"
                                                 style={{width: "100px", height: "100px"}}/>
                                        )}
                                    </div>
                                    <div className="user-info col-8">
                                        <div className="row">
                                            <div className="col"><span
                                                className="label">Name: </span> {this.state.userRow.firstName} {this.state.userRow.lastName}
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col"><span
                                                className="label">Username: </span>{this.state.userRow.username}</div>
                                        </div>
                                        <div className="row">
                                            <div className="col"><span
                                                className="label">Email: </span>{this.state.userRow.email}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="control-group">
                                    <div className="row">
                                        <div className="col-6"><span className="label">Status: </span></div>
                                    </div>
                                    <div className="row">
                                        <div className="col-6">
                                            <span className="form-group">
                                                <div inline="true" role="group" className="btn-group btn-group-sm">
                                                    <button type="button" onClick={(e) => this.enabledEvent(e, 1)}
                                                            className={this.state.userRow.isEnable ? 'btn btn-primary' : 'btn btn-secondary'}>Enable</button>
                                                    <button type="button" onClick={(e) => this.enabledEvent(e, 0)}
                                                            className={!this.state.userRow.isEnable ? 'btn btn-primary' : 'btn btn-secondary'}>Disable</button>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-6">
                                            <span className="label">Confirmed: </span>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col">
                                            <span className="form-group">
                                                <div inline="true" role="group" className="btn-group btn-group-sm">
                                                    <button type="button" onClick={(e) => this.confirmedEvent(e, 1)}
                                                            className={this.state.userRow.isConfirmed ? 'btn btn-primary' : 'btn btn-secondary'}>Yes</button>
                                                    <button type="button" onClick={(e) => this.confirmedEvent(e, 0)}
                                                            className={!this.state.userRow.isConfirmed ? 'btn btn-primary' : 'btn btn-secondary'}>No</button>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default List;