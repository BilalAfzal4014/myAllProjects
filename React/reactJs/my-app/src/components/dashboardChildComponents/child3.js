import React, {Component} from 'react';

class Child3 extends Component {
    constructor(props) {
        super(props);
        //console.log(this.props);
        //console.log(props.match.params.id);
        //console.log(this.props.match.params.id);
        /*this.state = {
            id: this.props.match.params.id
        }*/

        //this.props.match.params.path
        //this.props.match.params.url
    }

    componentWillReceiveProps(newProps) {
        //console.log(newProps.match.params.id);
        /*this.setState({
            id: newProps.match.params.id
        })*/
    }

    render() {
        return (
            /*<h1>i m Child3 component - {this.state.id}</h1>*/
            <h1>i m Child3 component - {this.props.match.params.id}</h1>
        );
    }
}

export default Child3;
