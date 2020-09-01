import React from 'react'
import {Link} from 'react-router-dom'

function ListFragment(props) {
    return (
        <React.Fragment>
            <table className="table table-bordered" id="dataTable" width="100%"
                   cellSpacing="0">
                <thead>
                <tr>
                    <th style={{"cursor": "pointer"}}
                        onClick={() => props.object.sortBy('id')}>ID
                        {props.is_order && props.sorting === 'id' &&
                        <i className={props.sort_class} aria-hidden="true"></i>
                        }
                    </th>
                    <th style={{"cursor": "pointer"}}
                        onClick={() => props.object.sortBy('title')}>Title
                        {props.is_order && props.sorting === 'title' &&
                        <i className={props.sort_class} aria-hidden="true"></i>
                        }
                    </th>
                    <th style={{"cursor": "pointer"}}
                        onClick={() => props.object.sortBy('message')}>Message
                        {props.is_order && props.sorting === 'message' &&
                        <i className={props.sort_class} aria-hidden="true"></i>
                        }
                    </th>
                    <th style={{"cursor": "pointer"}}
                        onClick={() => props.object.sortBy('created_at')}>Date Created
                        {props.is_order && props.sorting === 'created_at' &&
                        <i className={props.sort_class} aria-hidden="true"></i>
                        }
                    </th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {props.count && props.feedbacks.map((feedback, i) => (
                    <tr key={i}>
                        <td>{feedback.id}</td>
                        <td>{feedback.title}</td>
                        <td>{feedback.message}</td>
                        <td>{feedback.createdAt}</td>
                        <td>
                            {feedback.status === 'pending' &&
                            <button type="button" className="btn btn-info" title="Closed"
                                    onClick={(e) => props.object.changeStatus(i, feedback, 'closed')}>
                                Closed
                            </button>
                            }

                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
        </React.Fragment>
    )
}

export default ListFragment