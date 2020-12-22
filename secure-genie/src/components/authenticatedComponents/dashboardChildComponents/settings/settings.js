import React from 'react';

class Settings extends React.Component {
    constructor(props) {
        super(props);


    }


    render() {
        return (
            <div>
                <div className="breadcrumbs">
                    <ul className="list_none btns">
                        <li><a href="#">Edit <img src="/images/pencil_ico.png" alt="#"/></a></li>
                    </ul>
                    <ul className="list_none links">
                        <li>Profile Settings</li>
                    </ul>
                </div>
                <div className="pad_div">
                    <form className="add_user_form">
                        <div className="form_cols">
                            <div className="col">
                                <img src="/images/img1.jpg" alt="#" className="img-responsive user_dp"/>
                                <input type="file" id="dp" className="hidden"/>
                                <label htmlFor="dp" className="dp_changer">Change Photo</label>
                            </div>
                            <div className="col">
                                <label htmlFor="fname">Name</label>
                                <div className="input_holder">
                                    <img src="/images/ico7.png" alt="#"/>
                                    <input type="text" id="fname" placeholder="Mark Zukerberg"/>
                                </div>
                                <label htmlFor="phone">Phone</label>
                                <div className="input_holder">
                                    <img src="/images/ico8.png" alt="#"/>
                                    <input type="text" id="phone" placeholder="+ 92 333 568 7763 "/>
                                </div>
                            </div>
                            <div className="col">
                                <label htmlFor="email">Email</label>
                                <div className="input_holder">
                                    <img src="/images/ico10.png" alt="#"/>
                                    <input type="text" id="email" placeholder="mark.zukerberg@gmail.com"/>
                                </div>
                                <label htmlFor="pass">Password</label>
                                <div className="input_holder">
                                    <img src="/images/ico9.png" alt="#"/>
                                    <input type="password" id="pass" placeholder="Mark Zukerberg"
                                           style={{width: '64%'}}/>
                                    <a href="#" className="pass_change_btn">Change</a>
                                </div>
                                <div className="pass_changer">
                                    <label htmlFor="new_pass">New Password</label>
                                    <div className="input_holder">
                                        <img src="/images/ico9.png" alt="#"/>
                                        <input type="password" id="new_pass" placeholder="Mark Zukerberg2"
                                               className="ac_pass" onClick="myFunction()"/>
                                        <a href="#" className="btn_reveal" onClick="revealPass2(event)"><img
                                            src="/images/reveal_icon.png" alt="#"/></a>
                                    </div>
                                    <label htmlFor="confirm_pass">Re-type Password</label>
                                    <div className="input_holder">
                                        <img src="/images/ico9.png" alt="#"/>
                                        <input type="password" id="confirm_pass" placeholder="Mark Zukerberg2"
                                               className="ac_pass"/>
                                        <a href="#" className="btn_reveal" onClick="revealPass(event)"><img
                                            src="/images/reveal_icon.png" alt="#"/></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="SAVE"/>
                    </form>
                </div>
            </div>
        )
    }

}

export default Settings;