import React from 'react';

class RegulatorManagement extends React.Component {
    constructor(props) {
        super(props);


    }


    render() {
        return (
            <div>
                <div className="breadcrumbs">
                    <ul className="list_none links">
                        <li>Regulators Management</li>
                    </ul>
                </div>
                <div className="pad_div">
                    <form className="add_user_form departments">
                        <div className="form_cols">
                            <div className="col">
                                <label htmlFor="regulator-id">Regulator-id</label>
                                <div className="input_holder select">
                                    <img src="/images/ico13.png" alt="#"/>
                                    <select id="regulator-id">
                                        <option selected>ID</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                                <label htmlFor="department-id">Department-id</label>
                                <div className="input_holder select">
                                    <img src="/images/ico13.png" alt="#"/>
                                    <select id="department-id">
                                        <option selected>ID</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
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

export default RegulatorManagement;