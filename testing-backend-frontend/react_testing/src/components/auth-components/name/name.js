import React from "react";

class Name extends React.Component{

    constructor(props) {
        super(props);
    }

    updateCounter(){
        this.props.incrementCounter(this.props.counter);
    }

    render(){

        return (
            <div>
                <h1 data-testid={"h1-firstName"}>{this.props.firstName}</h1>
                <h1 data-testid={"h1-lastName"}>{this.props.lastName}</h1>
                <button id={"incBtn"} data-testid={"increment-counter"} onClick={this.updateCounter.bind(this)}>increment counter</button>
            </div>
        )
    }

}

export default Name;