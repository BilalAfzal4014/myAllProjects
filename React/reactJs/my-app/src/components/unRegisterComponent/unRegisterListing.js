import React, {Component} from 'react';
import {connect} from "react-redux";

import {registerUserActionDispatcher, pushIntoRegisterList} from "../../store/actions/registerAction";

class Unregister extends Component {

    constructor(props) {
        super(props);
        this.registerUser = this.registerUser.bind(this);
    }

    registerUser(user) {
        this.props.registerUserAction(user);
        this.props.pushIntoregisterList(user);
    }

    render() {
        return (
            <div>
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.props.unRegisterUsers.map((user) => (
                        <tr key={user.id}>
                            <td>{user.name}</td>
                            <td>{user.gender}</td>
                            <td>
                                <button onClick={this.registerUser.bind(this, user)}>register user</button>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        unRegisterUsers: state.unRegisterReducer
    }
};

const mapDispatchToProps = (dispatch) => {
    return {
        registerUserAction: (user) => {
            dispatch(registerUserActionDispatcher(user));
        },
        pushIntoregisterList: (user) => {
            dispatch(pushIntoRegisterList(user));
        }
    }
};

export default connect(mapStateToProps, mapDispatchToProps)(Unregister);
