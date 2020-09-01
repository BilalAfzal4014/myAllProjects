import React from 'react';


class Login extends React.Component {
    constructor(props) {
        super(props);

    }

    render() {
        return (
            <div className="login_screen">
                <div className="d_table">
                    <div className="v_middle">
                        <div className="login_form">
                            <form>
                                <a href="index.html" className="logo"><img src="images/logo.png" alt="SecureGenie" /></a>
                                <div className="input_holder"><input type="text" placeholder="Email address" /></div>
                                <div className="input_holder pass"><input type="password" placeholder="Password" /></div>
                                <div className="input_holder select">
                                    <select>
                                        <option selected>Select Type</option>
                                        <option>Core</option>
                                        <option>Legal</option>
                                        <option>Medical</option>
                                    </select>
                                </div>
                                <input type="submit" value="LOG IN" />
                                    <div className="checks_holder medium_font">
                                        <div className="left">
                                            <input type="checkbox" id="remember" className="hidden" checked />
                                            <label htmlFor="remember" className="custom_check">Remember Me</label>
                                        </div>
                                        <div className="right text_right">
                                            <a href="#">Forgot Password?</a>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

}

export default Login;
