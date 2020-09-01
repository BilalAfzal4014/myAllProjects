import React from "react";
import componentWrapper from "../HOC/HOCComponent"

function Button2(props) {
    return (
        <div>
            <button style={props.style}>button 2</button>
        </div>
    )
}

export default componentWrapper(Button2);