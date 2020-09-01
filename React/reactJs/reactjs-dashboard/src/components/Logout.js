import React, {Component} from 'react'

class Logout extends Component {
    constructor(props) {
        super(props)

        this.state = {}
    }

    componentWillMount() {
        delete localStorage.token
        this.props.history.push("/")
    }

    render() {
        return (
            <p>Logout</p>
        )
    }
}

export default Logout;