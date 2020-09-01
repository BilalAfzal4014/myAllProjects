import React, {Component} from 'react'
import Sidebar from '../layouts/Sidebar'
import Navbar from '../layouts/Navbar'
import axios from "axios";
import Footer from "../layouts/Footer";
import {Link} from "react-router-dom";
import Helping from '../../helpers/Helping'
import Notification from "../layouts/Notification";
import ScreenLoad from '../layouts/ScreenLoad'
import Pagination from "react-js-pagination";

class List extends Component {
    constructor(props) {
        super(props);

        this.state = {
            news: {},
            url: URL + 'news',
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

        this.deleteNews = this.deleteNews.bind(this)
        this.handlePageChange = this.handlePageChange.bind(this)
        this.handleSearchChange = this.handleSearchChange.bind(this)
        this.sortBy = this.sortBy.bind(this)
    }

    componentWillMount() {
        const {pathname} = this.props.location

        this.setState({
            routeName: pathname
        })

        this.getNews()
    }

    getNews() {
        let $this = this
        let search = this.state.search

        axios.get(this.state.url + "?page=" + this.state.activePage + "&limit=" + this.state.limit + "&search=" + search.trim() + "&sorting=" + this.state.sorting + "&sort_type="+this.state.sort_type, Helping.getConfig()).then(response => {
            $this.setState({
                news: response.data.payload.news,
                count: response.data.payload.news.length,
                total: response.data.payload.total
            })
            $this.setState({isLoading: false})
        }).catch(error => {
            $this.setState({isLoading: false})
            if (error.response.data.status === 404) {
                $this.setState({
                    total: 0
                })
                return false;
            }
            if (error.response) {
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
            this.getNews()
        });
    }

    deleteNews(e, object) {
        let $this = this

        if (window.confirm('Are you sure???')) {
            axios.delete(this.state.url + "/" + object.id, Helping.getConfig()).then(response => {
                const news = Helping.removeItem($this.state.news, object.id)

                $this.setState({
                    news: news
                })

                $this.setState({
                    error_message: 'News deleted successfully.',
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
                $this.getNews()
            }

            if ($this.state.search.length === 0) {
                $this.getNews()
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
            $this.getNews()
        })
    }

    render() {
        if (this.state.isLoading) {
            return (
                <ScreenLoad title="News"/>
            )
        }

        return (
            <div id="wrapper">
                <Sidebar history={this.props.history} routeName={this.state.routeName}/>
                <div id="content-wrapper" className="d-flex flex-column">
                    <div id="content">
                        <Navbar/>
                        <Link to="/create-news" style={{"marginLeft": "85%", "marginBottom": "10px"}}
                              className="btn btn-primary">Add
                            new news</Link>
                        <div className="container-fluid">
                            {this.state.error_message &&
                            <Notification type={this.state.error_type} message={this.state.error_message}/>
                            }
                            <div className="card shadow mb-4">
                                <div className="card-header py-3">
                                    <h6 className="m-0 font-weight-bold text-primary">News
                                        <input type="text" placeholder="Search" onBlur={this.handleSearchChange} onChange={this.handleSearchChange}
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
                                                    <th style={{"cursor": "pointer"}} onClick={() => this.sortBy('title')}>Title
                                                        {this.state.is_order && this.state.sorting === 'title' &&
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
                                                this.state.news.map((object, i) => (
                                                    <tr key={i}>
                                                        <td>{object.id}</td>
                                                        <td>{object.title}</td>
                                                        <td>{object.createdAt}</td>
                                                        <td>
                                                            <Link to={'edit-news/' + object.id}
                                                                  className="btn btn-info">
                                                                <i className="fas fa-pencil-alt"></i>
                                                            </Link>
                                                            &nbsp;

                                                            <button type="button" className="btn btn-danger"
                                                                    onClick={(e) => this.deleteNews(e, object)}>
                                                                <i className="fas fa-times"></i>
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
                                            <Notification type="danger" message="News not found."/>
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

export default List;