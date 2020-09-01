import React from "react";

class Header extends React.Component {
    constructor(props) {
        super(props);
    }


    render() {
        return (
            <header id="header">
                <a href="login.html" className="logo"><img src="/images/logo.png" alt="SecureGenie"/></a>
                <div className="align_right">
                    <form className="search_form">
                        <input type="search" placeholder="Search here"/>
                        <input type="submit" value="Search"/>
                    </form>
                    <a href="#" className="user_link"><img src="/images/bell_ico.png" alt="#"/></a>
                </div>
            </header>
        )
    }
}

export default Header;
