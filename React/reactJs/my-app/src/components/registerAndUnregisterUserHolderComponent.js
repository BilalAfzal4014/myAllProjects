import React, {Component} from 'react';
import RegisterComponent from "./registerComponent/registerListing";
import UnregisterComponent from "./unRegisterComponent/unRegisterListing";

class RegisterAndUnregisterUserHolder extends Component {


    render() {
        return (
            <div>
                <div style={{display: 'inline-block', width: '50%'}}>
                    <RegisterComponent></RegisterComponent>
                </div>
                <div style={{'display': 'inline-block', width: '50%'}}>
                    <UnregisterComponent></UnregisterComponent>
                </div>
            </div>
        );
    }
}

export default RegisterAndUnregisterUserHolder;
