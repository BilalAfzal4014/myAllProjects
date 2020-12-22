import React, {Component} from 'react';
import {connect} from 'react-redux';
import {unRegisterUserActionDispatcher, pushIntoUnregisterList} from "../../store/actions/unRegisterAction"

class Register extends Component {

    constructor(props) {
        super(props);
        this.unregisterUser = this.unregisterUser.bind(this);
    }

    unregisterUser(user) {
        this.props.unregisterUserAction(user);
        this.props.pushIntoUnregisterList(user);
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
                    {this.props.registerUsers.map((user) =>
                        <tr key={user.id}>
                            <td>{user.name}</td>
                            <td>{user.gender}</td>
                            <td>
                                <button onClick={this.unregisterUser.bind(this, user)}>unRegister user</button>
                            </td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        registerUsers: state.registerReducer
    }
};

const mapDispatchToProps = (dispatch) => {
    return {
        unregisterUserAction: (user) => {
            dispatch(unRegisterUserActionDispatcher(user));
        },
        pushIntoUnregisterList: (user) => {
            dispatch(pushIntoUnregisterList(user));
        }
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(Register);
