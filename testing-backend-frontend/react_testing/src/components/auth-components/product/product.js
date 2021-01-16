import React from "react";
import {connect} from "react-redux";
import {insertCategory} from "../../../store/actions/category-action";


class Product extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: 3
        }
    }

    addUser() {
        this.props.insertUser({
            id: this.state.id,
            name: "Mark Jobs",
            gender: "Male"
        });

        this.setState((state) => ({id: state.id + 1}))
    }

    render() {
        return (
            <div>
                <h1>I am Product component</h1>
                <button onClick={this.addUser.bind(this)}>click to add user</button>
            </div>
        )
    }
}

const mapDispatchToProps = (dispatch) => ({
    insertUser: (user) => {
        dispatch(insertCategory(user));
    }
})
const mapStateToProps = (store) => ({

})

export default connect(mapStateToProps, mapDispatchToProps)(Product);