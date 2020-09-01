import React, {Component} from 'react'

class Notification extends Component {
    constructor(props) {
        super(props);

        this.state = {}
    }

    render() {
        if (this.props.message.length > 0) {
            return (
                <div className={'alert alert-' + this.props.type}>
                    {this.props.message}
                </div>
            )
        }

        return (
            <div></div>
        );
    }
}

export default Notification