import React from 'react';

class DepartmentListing extends React.Component {
    constructor(props) {
        super(props);


    }


    render() {
        return (
            <div>
                <div className="breadcrumbs">
                    <ul className="list_none links">
                        <li>Documents</li>
                    </ul>
                </div>
                <div className="pad_div">
                    <div className="data_table">
                        <table id="example" className="display " style={{
                            width:'100%',
                            background:'#fff'
                        }}>
                            <thead>
                            <th>File Owner Name</th>
                            <th>Document Name</th>
                            <th>Document Type</th>
                            <th>Shared with</th>
                            <th>Date</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">
                                        User Name<br/>
                                        email@gmail.com<br/>
                                        sdkuafyulbfydufbyaubygfudbygalyayufab
                                    </span>
                                </td>
                                <td>Blood Result Report</td>
                                <td>Medical</td>
                                <td>Alex Hills</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="document-history.html" className="details_btn"><img
                                    src="/images/eye_ico.png"
                                    alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">
                                        User Name<br/>
                                        email@gmail.com<br/>
                                        sdkuafyulbfydufbyaubygfudbygalyayufab
                                    </span>
                                </td>
                                <td>Blood Result Report</td>
                                <td>Medical</td>
                                <td>Alex Hills</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="document-history.html" className="details_btn"><img
                                    src="/images/eye_ico.png"
                                    alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">
                                        User Name<br/>
                                        email@gmail.com<br/>
                                        sdkuafyulbfydufbyaubygfudbygalyayufab
                                    </span>
                                </td>
                                <td>Blood Result Report</td>
                                <td>Medical</td>
                                <td>Alex Hills</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="document-history.html" className="details_btn"><img
                                    src="/images/eye_ico.png"
                                    alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">
                                        User Name<br/>
                                        email@gmail.com<br/>
                                        sdkuafyulbfydufbyaubygfudbygalyayufab
                                    </span>
                                </td>
                                <td>Blood Result Report</td>
                                <td>Medical</td>
                                <td>Alex Hills</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="document-history.html" className="details_btn"><img
                                    src="/images/eye_ico.png"
                                    alt="Details"/></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/user2.jpg" alt="#"/>
                                    <span className="name">
                                        User Name<br/>
                                        email@gmail.com<br/>
                                        sdkuafyulbfydufbyaubygfudbygalyayufab
                                    </span>
                                </td>
                                <td>Blood Result Report</td>
                                <td>Medical</td>
                                <td>Alex Hills</td>
                                <td>15 May 2016 <em>10 days ago</em></td>
                                <td><a href="document-history.html" className="details_btn"><img
                                    src="/images/eye_ico.png"
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

export default DepartmentListing;