import React from 'react';
import signUpUser from "../../sdk/userAuthenticationManager/signUp";
import userLogin from "../../sdk/userAuthenticationManager/login";
import {intializeFireBase} from "../../sdk/fireBaseIntegration/makeFirebaseConnection";
import {connect} from "react-redux";
import {saveTokenAction} from "../../store/actions/userLoginAction";
import {withRouter} from "react-router-dom";

class Login extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            users: [],
        };
        this.jsObj = {
            loadedFirst: true
        }
    }

    componentDidMount() {
        signUpUser.fetchUsersList((users) => {
            this.setState({
                users: users
            });
        });
    }

    InitializePlatform(user) {
        userLogin.loginViaCompanyKey(user, (isValidate) => {
            if (isValidate) {
                if (this.jsObj.loadedFirst) {
                    intializeFireBase((token) => {
                        userLogin.InitializePlatform(user, token, (data) => {
                            this.saveUserToken(data);
                        });
                    });
                    this.jsObj.loadedFirst = false;
                } else {
                    userLogin.InitializePlatform(user, null, (data) => {
                        this.saveUserToken(data);
                    });
                }
            }
        });
    }

    saveUserToken(data) {
        if (data != null) {
            this.props.saveToken(data);
            this.props.history.push("/dashboard");
        }
    }

    render() {
        return (
            <div>
                <ul className="list_none">
                    {
                        this.state.users.map((user, index) => (
                            <li key={index} onClick={this.InitializePlatform.bind(this, user)}>
                                <span>{user.user_id} {user.firstname} web</span><br/>
                                <span>Language: {user.language}</span><br/>
                                <span>{user.app_name}</span><br/>
                                <span>{user.company_key}</span><br/>
                            </li>
                        ))
                    }
                </ul>
            </div>
        );
    }

}

const mapStateToProps = (state) => {
    return {};
};

const mapDispatchToProps = (dispatch) => {
    return {
        saveToken: (userInfo) => {
            dispatch(saveTokenAction(userInfo));
        }
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(withRouter(Login));
