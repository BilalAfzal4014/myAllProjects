import React from 'react';
import {connect} from "react-redux";
import {withRouter} from "react-router-dom";
import signUpUser from "../../sdk/userAuthenticationManager/signUp";


class SignUp extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            user: {
                mode: "register",
                user_id: "",
                firstname: "",
                lastname: "",
                username: "",
                email: "",
                device_token: "",
                'instance-id': "",
                resource: "",
                phone_number: "",
                country: "",
                longitude: "",
                latitude: "",
                image_url: "",
                company_key: "",
                language: "",
                app_name: this.props.fcmConfig.projectId,
                app_id: this.props.fcmConfig.appId,
            }
        };
    }

    signUp() {
        signUpUser.signUp(this.state.user, () => {
            this.props.history.push("/");
        });
    }

    changeInput(key, event) {
        let user = {
            ...this.state.user
        };
        user[key] = event.target.value;
        this.setState({
            user
        });
    }

    render() {
        return (
            <div className="table_div_holder">
                <div className="center_allign_content_vertical">
                    <div>
                        <h1>Test Engagement Platform</h1>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'user_id')} type="text"
                                   value={this.state.user.user_id} placeholder="user id"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'firstname')}
                                   value={this.state.user.firstname}
                                   type="text"
                                   placeholder="First Name *"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'lastname')} type="text"
                                   value={this.state.user.lastname}
                                   placeholder="Last Name *"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'email')} type="text"
                                   value={this.state.user.email}
                                   placeholder="Email *"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'country')} type="text"
                                   value={this.state.user.country}
                                   placeholder="Country *"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'phone_number')}
                                   value={this.state.user.phone_number}
                                   type="text" placeholder="Phone"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" onChange={this.changeInput.bind(this, 'company_key')}
                                   value={this.state.user.company_key}
                                   type="text" placeholder="Insert Company Key"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="submit_input" type="submit" value="Create Account"
                                   onClick={this.signUp.bind(this)}/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" type="text" onChange={this.changeInput.bind(this, 'language')}
                                   value={this.state.user.language}
                                   placeholder="Insert Language Code like en"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" type="text" disabled
                                   value={this.state.user.app_name}
                                   placeholder="Insert App Name"/>
                        </div>
                        <div className="input_div_holder">
                            <input className="text_input" type="text" disabled
                                   value={this.state.user.app_id}
                                   placeholder="Insert App ID"/>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

}

const mapStateToProps = (state) => {
    return {
        fcmConfig: state.fcmConfiguration
    };
};

const mapDispatchToProps = (dispatch) => {
    return {};
};

export default connect(mapStateToProps, mapDispatchToProps)(withRouter(SignUp));
