import React from "react";
import {connect} from "react-redux";
import {removeCategory} from "../../../store/actions/category-action";
import {getCategories} from "./category-api";
import {insertCategory} from "../../../store/actions/category-action";


class Category extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            table: [{
                id: 1,
                name: "ABC"
            }, {
                id: 2,
                name: "XYZ"
            }]
        }
    }

    componentDidMount() {
        getCategories()
            .then((result) => {
                for (let category of result) {
                    this.props.insertUser(category);
                }
            })
    }

    removeCategory() {
        this.props.removeCategoryFromStore({
            id: 1
        });
    }

    render() {
        return (
            <div>
                <h1>I am Category component</h1>
                <h1>{JSON.stringify(this.props.categories)}</h1>
                <table>
                    <thead>
                    <tr>
                        <td>id</td>
                        <td>name</td>
                    </tr>
                    </thead>
                    <tbody>
                    {
                        this.state.table.map((row, index) => (
                            <tr key={row.id}>
                                <td>{row.id}</td>
                                <td>{row.name}</td>
                            </tr>
                        ))
                    }
                    </tbody>
                </table>
                <button onClick={this.removeCategory.bind(this)}>remove user</button>
            </div>
        )
    }
}


const mapStateToProps = (store) => {
    return {
        categories: store.sampleReducer
    }
}


const mapDispatchToProps = (dispatch) => {
    return {
        removeCategoryFromStore: (data) => {
            dispatch(removeCategory(data));
        },
        insertUser: (user) => {
            dispatch(insertCategory(user));
        }
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Category);