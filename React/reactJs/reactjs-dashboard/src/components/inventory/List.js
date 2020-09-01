import React, {Component} from 'react'
import Sidebar from '../layouts/Sidebar'
import Navbar from '../layouts/Navbar'
import axios from "axios";
import Footer from "../layouts/Footer";
import {Link} from "react-router-dom";
import Helping from "../../helpers/Helping";
import Notification from "../layouts/Notification";
import ScreenLoad from "../layouts/ScreenLoad";
import Pagination from "react-js-pagination";

class List extends Component {
    constructor(props) {
        super(props);

        this.state = {
            inventories: {},
            url: URL + 'inventory-list',
            count: '',
            routeName: '',
            error_message: '',
            error_type: '',
            isLoading: true,
            total: 0,
            activePage: 1,
            limit: 10,
            search: '',
            sorting: 'id',
            sort_type: 'DESC',
            sort_class: 'fa fa-sort-up',
            is_order: false
        }

        this.deleteInventory = this.deleteInventory.bind(this)
        this.handlePageChange = this.handlePageChange.bind(this)
        this.handleSearchChange = this.handleSearchChange.bind(this)
        this.sortBy = this.sortBy.bind(this)
    }

    componentWillMount() {
        const {pathname} = this.props.location

        this.setState({
            routeName: pathname
        })

        this.getInventories()
    }

    handlePageChange(pageNumber) {
        if (this.state.activePage === pageNumber) {
            return false;
        }

        this.setState({
            activePage: pageNumber,
            isLoading: true
        }, () => {
            this.getInventories()
        });
    }

    getInventories() {
        let $this = this
        let search = this.state.search

        axios.get(this.state.url + "?page=" + this.state.activePage + "&limit=" + this.state.limit + "&search=" + search.trim() + "&sorting=" + this.state.sorting + "&sort_type="+this.state.sort_type, Helping.getConfig()).then(response => {
            $this.setState({
                inventories: response.data.payload.inventory,
                count: response.data.payload.inventory.length,
                total: response.data.payload.total
            })
            $this.setState({isLoading: false})
        }).catch(error => {
            document.getElementById('nprogress').style.display = 'none';
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

    deleteInventory(e, inventory) {
        let $this = this

        if (window.confirm('Are you sure???')) {
            axios.delete(URL + "delete-inventory/" + inventory.id, Helping.getConfig()).then(response => {
                const inventories = Helping.removeItem(this.state.inventories, inventory.id)

                this.setState({
                    inventories: inventories
                })

                $this.setState({
                    error_message: 'Inventory deleted successfully.',
                    error_type: 'success'
                })
                document.getElementById('nprogress').style.display = 'none';
            }).catch(error => {
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
            if ($this.state.search.length > 3) {
                $this.getInventories()
            }

            if ($this.state.search.length === 0) {
                $this.getInventories()
            }
        })
    }

    sortBy(key) {
        let $this = this
        let sortType = this.state.sort_type
        let sortClass = this.state.sort_class

        if(this.state.sort_type === 'ASC') {
            sortType = 'DESC'
            sortClass = "fa fa-sort-up"
        }

        if(this.state.sort_type === 'DESC') {
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
            $this.getInventories()
        })
    }

    render() {
        if (this.state.isLoading) {
            return (
                <ScreenLoad title="Inventory List"/>
            )
        }


        return (
            <div>
                <div id="wrapper">
                    <Sidebar history={this.props.history} routeName={this.state.routeName}/>
                    <div id="content-wrapper" className="d-flex flex-column">
                        <div id="content">
                            <Navbar/>
                            <Link to="/create-inventory" style={{"marginLeft": "84%", "marginBottom": "10px"}}
                                  className="btn btn-primary">Create Inventory</Link>
                            <div className="container-fluid">
                                {this.state.error_message &&
                                <Notification type={this.state.error_type} message={this.state.error_message}/>
                                }

                                <div className="card shadow mb-4">
                                    <div className="card-header py-3">
                                        <h6 className="m-0 font-weight-bold text-primary">Inventory List
                                            <input type="text" placeholder="Search" onChange={this.handleSearchChange}
                                                   value={this.state.search} className="form-control"
                                                   style={{"width": "30%", "float": "right"}}/>
                                        </h6>
                                    </div>
                                    <div className="card-body">
                                        {this.state.total > 0 ? (
                                            <div className="table-responsive">
                                                <table className="table table-bordered" id="dataTable" width="100%"
                                                       cellSpacing="0">
                                                    <thead>
                                                    <tr>
                                                        <th style={{"cursor": "pointer"}} onClick={() => this.sortBy('id')}>ID
                                                            {this.state.is_order && this.state.sorting === 'id' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}} onClick={() => this.sortBy('name')}>Name
                                                            {this.state.is_order && this.state.sorting === 'name' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}} onClick={() => this.sortBy('slug')}>Slug
                                                            {this.state.is_order && this.state.sorting === 'slug' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th style={{"cursor": "pointer"}} onClick={() => this.sortBy('created_at')}>Date Created
                                                            {this.state.is_order && this.state.sorting === 'created_at' &&
                                                            <i className={this.state.sort_class} aria-hidden="true"></i>
                                                            }
                                                        </th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this.state.count &&
                                                    this.state.inventories.map((inventory, i) => (
                                                        <tr key={i}>
                                                            <td>{inventory.id}</td>
                                                            <td>{inventory.name} </td>
                                                            <td>{inventory.slug}</td>
                                                            <td>{inventory.createdAt}</td>
                                                            <td>
                                                                <Link to={'edit-inventory/' + inventory.id}
                                                                      className="btn btn-info">
                                                                    <i className="fas fa-pencil-alt"></i>
                                                                </Link>
                                                                &nbsp;

                                                                <button type="button" className="btn btn-danger"
                                                                        onClick={(e) => this.deleteInventory(e, inventory)}>
                                                                    <i className="fas fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    ))}
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
                                        ): (
                                            <div className="table-responsive">
                                                <Notification type="danger" message="Inventories not found."/>
                                            </div>
                                        )}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <Footer/>
                    </div>
                </div>
            </div>
        )
    }
}

export default List;