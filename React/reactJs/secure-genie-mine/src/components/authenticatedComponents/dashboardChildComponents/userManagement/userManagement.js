import React from 'react';

class UserManagement extends React.Component {
    constructor(props) {
        super(props);


    }


    render() {
        return (
            <div>
                <div className="breadcrumbs">
                    <ul className="list_none btns">
                        <li><a href="#"><img src="/images/ico15.png" alt="#" className="left"/> Filters</a></li>
                    </ul>
                    <ul className="list_none links">
                        <li>User Management</li>
                    </ul>
                </div>
                <div className="pad_div">
                    <div className="data_table">
                        <table id="example" className="display " style={{
                            width: "100%",
                            background: "#fff"
                        }}>
                            <thead>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Created on</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">User Name</span>
                                </td>
                                <td>Last Name</td>
                                <td>email123@gmail.com</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="details.html" className="details_btn"><img src="/images/eye_ico.png"
                                                                                        alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">User Name</span>
                                </td>
                                <td>Last Name</td>
                                <td>email123@gmail.com</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="details.html" className="details_btn"><img src="/images/eye_ico.png"
                                                                                        alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">User Name</span>
                                </td>
                                <td>Last Name</td>
                                <td>email123@gmail.com</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="details.html" className="details_btn"><img src="/images/eye_ico.png"
                                                                                        alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">User Name</span>
                                </td>
                                <td>Last Name</td>
                                <td>email123@gmail.com</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="details.html" className="details_btn"><img src="/images/eye_ico.png"
                                                                                        alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">User Name</span>
                                </td>
                                <td>Last Name</td>
                                <td>email123@gmail.com</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="details.html" className="details_btn"><img src="/images/eye_ico.png"
                                                                                        alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">User Name</span>
                                </td>
                                <td>Last Name</td>
                                <td>email123@gmail.com</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="details.html" className="details_btn"><img src="/images/eye_ico.png"
                                                                                        alt="Details"/></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        )
    }

}

export default UserManagement;