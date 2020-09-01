import React, {Component} from 'react'
import {Link} from "react-router-dom";

class ListFragment extends Component {
    constructor(props) {
        super(props)

        this.state = {}
    }

    render() {
        return (
            <React.Fragment>
                <table className="table table-bordered" id="dataTable" width="100%"
                       cellSpacing="0">
                    <thead>
                    <tr>
                        <th style={{"cursor": "pointer"}}
                            onClick={() => this.props.object.sortBy('id')}>ID
                            {this.props.is_order && this.props.sorting === 'id' &&
                            <i className={this.props.sort_class} aria-hidden="true"></i>
                            }
                        </th>
                        <th style={{"cursor": "pointer"}}
                            onClick={() => this.props.object.sortBy('name')}>Name
                            {this.props.is_order && this.props.sorting === 'name' &&
                            <i className={this.props.sort_class} aria-hidden="true"></i>
                            }
                        </th>
                        <th style={{"cursor": "pointer"}}
                            onClick={() => this.props.object.sortBy('created_at')}>Date Created
                            {this.props.is_order && this.props.sorting === 'created_at' &&
                            <i className={this.props.sort_class} aria-hidden="true"></i>
                            }
                        </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.props.count &&
                    this.props.categories.map((category, i) => (
                        <tr key={i}>
                            <td>{category.id}</td>
                            <td>{category.name}</td>
                            <td>{category.createdAt}</td>
                            <td>
                                <Link to={'edit-category/' + category.id}
                                      className="btn btn-info">
                                    <i className="fas fa-pencil-alt"></i>
                                </Link>
                                &nbsp;

                                <button type="button" className="btn btn-danger"
                                        onClick={(e) => this.props.object.deleteCategory(e, category)}>
                                    <i className="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    ))
                    }
                    </tbody>
                </table>
            </React.Fragment>
        )
    }
}

export default ListFragment