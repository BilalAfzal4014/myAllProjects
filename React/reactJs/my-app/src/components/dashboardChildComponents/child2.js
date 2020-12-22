import React, {Component} from 'react';
import {Redirect} from 'react-router-dom';

class Child2 extends Component {
    constructor(props) {
        super(props);
    }


    render() {

        return (

            <Redirect to='/dashboard/child1' />
        );
    }
}

export default Child2;
