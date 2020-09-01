import React, {Component} from 'react';

class Child1 extends Component {
    constructor(props) {
        super(props);
        //console.log(global.text);
        //console.log("child1 props", this.props);
    }

    /*componentWillMount() {
    }

    componentDidMount() {
    }

    componentWillUpdate(nextProps, nextState, nextContext) {

    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        //here we will recieve updated props
        console.clear();
        console.log(this.props.location.pathname);
    }

    componentWillReceiveProps(nextProps, nextContext) {

    }

    componentDidCatch(error, errorInfo) {
    }*/


    render() {
        return (
            <h1>i m Child1 component - {global.text}</h1>
        );
    }
}

export default Child1;
