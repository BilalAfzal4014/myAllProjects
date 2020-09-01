import React from "react";
import componentWrapper from "../HOC/HOCComponent"

/*class Button1 extends React.Component{
    constructor(props) {
        super(props);
        console.log("c", this.props);
    }

    render(){
        console.log("r", this.props);
        return(
            <div>
                <button style={this.props.style}>button 1</button>
            </div>
        )
    }
}*/

function Button1(props) {

    return (
        <div>
            <button style={props.style}>button 1</button>
        </div>
    )
}

export default componentWrapper(Button1);