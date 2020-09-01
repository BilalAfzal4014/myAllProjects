import React, {Component} from 'react';

class Root extends Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.match.params.justForTesting
        }
    }

    componentWillReceiveProps(newProps) {
        this.setState({
            id: newProps.match.params.justForTesting
        })
    }

    render() {
        return (
            <h1>i m root component - {this.state.id}</h1>
        );
    }
}

export default Root;
