import React, {Component} from 'react'
import axios from 'axios'

class Login extends Component {
    constructor(props) {
        super(props)

        this.state = {
            url: URL + "admin/login",
            username: "admin",
            password: "mickymouse1L-",
            error_message: ""
        }

        this.onLogin = this.onLogin.bind(this)
        this.handleUserNameChange = this.handleUserNameChange.bind(this)
        this.handlePasswordChange = this.handlePasswordChange.bind(this)
    }

    componentWillMount() {
        let $this = this

        if(localStorage.token) {
            $this.props.history.push("/dashboard")
        }
    }

    componentDidMount() {
    }
   
    onLogin() {
        let $this = this

        let config = {
            headers: {
                'Content-Type': 'application/json'
            }
        }

        let data = {
            username: this.state.username,
            password: this.state.password
        }

        axios.post($this.state.url, data, config).then(response => {
            localStorage.token = response.data.payload.accessToken
            $this.props.history.push("/dashboard")
        }).catch(error => {
            console.log(error.response)
            $this.setState({
                error_message: error.response.data.message
            });
        })
    }

    handleUserNameChange(e) {
        this.setState({
            username: e.target.value
        })
    }

    handlePasswordChange(e) {
        this.setState({
            password: e.target.value
        })
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-xl-6 col-lg-6 col-md-6">
                        <div className="card o-hidden border-0 shadow-lg my-5">
                            <div className="card-body p-0">
                                <div className="row">
                                  <div className="main_logo col-lg-6 d-none d-lg-block " >
                                 
                                  </div>
                                    <div className="col-lg-12">
                                        <div className="p-5">
                                            <div className="text-center">
                                                <h1 className="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                                { this.state.error_message &&
                                                    <div className="alert alert-danger">
                                                        {this.state.error_message}
                                                    </div>
                                                }
                                            </div>
                                            <form className="user">
                                                <div className="form-group">
                                                    <input type="email" className="form-control form-control-user"
                                                           aria-describedby="emailHelp"
                                                           placeholder="Enter Username"
                                                           value={this.state.username}
                                                           onChange={this.handleUserNameChange}/>
                                                </div>
                                                <div className="form-group">
                                                    <input type="password" className="form-control form-control-user"
                                                           placeholder="Password" value={this.state.password}
                                                           onChange={this.handlePasswordChange}/>
                                                </div>
                                                <div className="form-group">
                                                    <div className="custom-control custom-checkbox small">
                                                        <input type="checkbox" className="custom-control-input"
                                                               id="customCheck"/>
                                                        <label className="custom-control-label"
                                                               htmlFor="customCheck">Remember Me</label>
                                                    </div>
                                                </div>
                                                <button type="button" onClick={this.onLogin}
                                                        className="btn btn-primary btn-user btn-block">
                                                    Login
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        )
    }
}

export default Login;
